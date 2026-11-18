<?php

/** GitHub updater used only by the Themify Builder add-ons plugin. */
class PICTS_Builder_Addons_Updater {
    private $file;
    private $basename;
    private $username;
    private $repository;
    private $authorize_token;
    private $github_response;
    private $download_url;

    public function __construct( $file ) {
        $this->file = $file;
        // This must also be available in WP-Cron and connector requests, where
        // admin_init does not necessarily run.
        $this->basename = plugin_basename( $file );
    }

    public function set_username( $username ) { $this->username = $username; }
    public function set_repository( $repository ) { $this->repository = $repository; }
    public function authorize( $token ) { $this->authorize_token = $token; }

    private function get_repository_info() {
        if ( $this->github_response !== null ) { return; }
        $this->github_response = false;
        $url = sprintf( 'https://api.github.com/repos/%s/%s/releases', rawurlencode( $this->username ), rawurlencode( $this->repository ) );
        $args = array( 'timeout' => 10, 'redirection' => 0 );
        if ( $this->authorize_token ) { $args['headers']['Authorization'] = 'token ' . $this->authorize_token; }
        $response = wp_remote_get( $url, $args );
        if ( is_wp_error( $response ) ) { return; }
        $releases = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( ! is_array( $releases ) || empty( $releases[0] ) || ! is_array( $releases[0] ) ) { return; }
        $release = $releases[0];
        if ( empty( $release['tag_name'] ) || ! is_string( $release['tag_name'] ) || empty( $release['zipball_url'] ) || ! is_string( $release['zipball_url'] ) || ! $this->is_own_package( $release['zipball_url'] ) ) { return; }
        $this->github_response = $release;
    }

    private function is_own_package( $url ) {
        $parts = wp_parse_url( $url );
        $prefix = '/repos/' . rawurlencode( $this->username ) . '/' . rawurlencode( $this->repository ) . '/zipball/';
        return is_array( $parts ) && ( $parts['scheme'] ?? '' ) === 'https' && strtolower( $parts['host'] ?? '' ) === 'api.github.com'
            && ! isset( $parts['user'] ) && ! isset( $parts['pass'] ) && ! isset( $parts['port'] )
            && strpos( $parts['path'] ?? '', $prefix ) === 0 && strlen( $parts['path'] ?? '' ) > strlen( $prefix );
    }

    public function initialize() {
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
        add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3 );
        add_filter( 'upgrader_pre_download', array( $this, 'prepare_download' ), 10, 4 );
        add_filter( 'upgrader_source_selection', array( $this, 'source_folder' ), 10, 4 );
        add_action( 'upgrader_process_complete', array( $this, 'clear_download' ), 10, 0 );
    }

    public function modify_transient( $transient ) {
        if ( ! is_object( $transient ) || ! isset( $transient->checked[$this->basename] ) || ! is_string( $transient->checked[$this->basename] ) ) { return $transient; }
        $this->get_repository_info();
        if ( ! $this->github_response || ! version_compare( $this->github_response['tag_name'], $transient->checked[$this->basename], 'gt' ) ) { return $transient; }
        if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) { $transient->response = array(); }
        if ( isset( $transient->no_update[$this->basename] ) ) { unset( $transient->no_update[$this->basename] ); }
        $transient->response[$this->basename] = (object) array(
            'url' => 'https://github.com/' . $this->username . '/' . $this->repository,
            'slug' => dirname( $this->basename ),
            'package' => $this->github_response['zipball_url'],
            'new_version' => $this->github_response['tag_name'],
        );
        return $transient;
    }

    public function plugin_popup( $result, $action, $args ) {
        if ( $action !== 'plugin_information' || ! is_object( $args ) || ( $args->slug ?? '' ) !== dirname( $this->basename ) ) { return $result; }
        $this->get_repository_info();
        if ( ! $this->github_response ) { return $result; }
        if ( ! function_exists( 'get_plugin_data' ) ) { require_once ABSPATH . 'wp-admin/includes/plugin.php'; }
        $plugin = get_plugin_data( $this->file );
        return (object) array(
            'name' => $plugin['Name'] ?? '', 'slug' => dirname( $this->basename ),
            'version' => $this->github_response['tag_name'], 'author' => $plugin['AuthorName'] ?? '',
            'author_profile' => $plugin['AuthorURI'] ?? '', 'homepage' => $plugin['PluginURI'] ?? '',
            'last_updated' => $this->github_response['published_at'] ?? '',
            'sections' => array( 'description' => $plugin['Description'] ?? '', 'changelog' => $this->github_response['body'] ?? '' ),
            'download_link' => $this->github_response['zipball_url'],
        );
    }

    /** Preserve other updaters' replies. Never attach credentials to other packages. */
    public function prepare_download( $reply, $package, $upgrader, $extra ) {
        if ( $reply !== false || ( $extra['plugin'] ?? '' ) !== $this->basename || ! $this->authorize_token || ! is_string( $package ) || ! $this->is_own_package( $package ) ) { return $reply; }
        $this->download_url = $package;
        add_filter( 'http_request_args', array( $this, 'download_package' ), 15, 2 );
        return $reply;
    }

    public function download_package( $args, $url ) {
        if ( $url !== $this->download_url || ! $this->is_own_package( $url ) ) { return $args; }
        // No cross-host redirect may carry a token. A private release requiring a
        // redirect fails closed; the current public release uses no token.
        $args['headers']['Authorization'] = 'token ' . $this->authorize_token;
        $args['redirection'] = 0;
        $this->clear_download();
        return $args;
    }

    public function clear_download() {
        remove_filter( 'http_request_args', array( $this, 'download_package' ), 15 );
        $this->download_url = null;
    }

    /** GitHub zipballs use a variable folder name: rename only our own source. */
    public function source_folder( $source, $remote_source, $upgrader, $extra ) {
        if ( is_wp_error( $source ) || ( $extra['plugin'] ?? '' ) !== $this->basename ) { return $source; }
        global $wp_filesystem;
        if ( ! $wp_filesystem || ! $wp_filesystem->exists( trailingslashit( $source ) . basename( $this->basename ) ) ) {
            return new WP_Error( 'picts_builder_package_invalid', 'The add-ons package does not contain the expected plugin file.' );
        }
        $folder = dirname( $this->basename );
        if ( basename( untrailingslashit( $source ) ) === $folder ) { return $source; }
        $destination = trailingslashit( $remote_source ) . $folder;
        if ( $wp_filesystem->exists( $destination ) || ! $wp_filesystem->move( $source, $destination, false ) ) {
            return new WP_Error( 'picts_builder_package_folder', 'The add-ons package folder could not be prepared.' );
        }
        return trailingslashit( $destination );
    }
}
