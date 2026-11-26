<?php

defined('ABSPATH') || exit;

class Picts_Builder_Addons_Updater
{
    private const RELEASE_API = 'https://api.github.com/repos/primaryict/picts-builder-addons';
    private const CACHE_KEY = 'picts_builder_addons_github_release';

    private $file;
    private $basename;
    private $slug;

    public function __construct($file)
    {
        $this->file = $file;
        $this->basename = plugin_basename($file);
        $this->slug = dirname($this->basename);
    }

    public function initialize()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'modify_transient']);
        add_filter('plugins_api', [$this, 'plugin_popup'], 10, 3);
        add_filter('upgrader_source_selection', [$this, 'select_source'], 10, 4);
    }

    private function get_release()
    {
        $cached = get_site_transient(self::CACHE_KEY);
        if (is_array($cached)) {
            return $cached;
        }

        $response = wp_remote_get(self::RELEASE_API . '/releases/latest', [
            'timeout' => 5,
            'headers' => ['Accept' => 'application/vnd.github+json'],
        ]);

        $release = [];
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            if (is_array($data) && is_string($data['tag_name'] ?? null)
                && preg_match('/^v?([0-9]+(?:\.[0-9]+)+)\z/', $data['tag_name'], $matches)
                && empty($data['draft']) && empty($data['prerelease'])
            ) {
                $package = self::RELEASE_API . '/zipball/' . rawurlencode($data['tag_name']);
                if (($data['zipball_url'] ?? '') === $package) {
                    $release = [
                        'version' => $matches[1],
                        'package' => $package,
                        'date' => is_string($data['published_at'] ?? null) ? $data['published_at'] : '',
                        'notes' => is_string($data['body'] ?? null) ? $data['body'] : '',
                    ];
                }
            }
        }

        set_site_transient(self::CACHE_KEY, $release, ($release ? 15 : 5) * MINUTE_IN_SECONDS);
        return $release;
    }

    public function modify_transient($transient)
    {
        if (!is_object($transient) || !is_array($transient->checked ?? null)) {
            return $transient;
        }

        $installed = $transient->checked[$this->basename] ?? '';
        if (!is_string($installed) || $installed === '') {
            return $transient;
        }

        $release = $this->get_release();
        if ($release && version_compare($release['version'], $installed, '>')) {
            if (!is_array($transient->response ?? null)) {
                $transient->response = [];
            }
            $transient->response[$this->basename] = (object) [
                'plugin' => $this->basename,
                'slug' => $this->slug,
                'url' => 'https://github.com/primaryict/picts-builder-addons/releases',
                'package' => $release['package'],
                'new_version' => $release['version'],
            ];
            if (is_array($transient->no_update ?? null)) {
                unset($transient->no_update[$this->basename]);
            }
        }

        return $transient;
    }

    public function plugin_popup($result, $action, $args)
    {
        if ($action !== 'plugin_information' || ($args->slug ?? '') !== $this->slug) {
            return $result;
        }

        $release = $this->get_release();
        if (!$release) {
            return $result;
        }

        $plugin = get_file_data($this->file, [
            'name' => 'Plugin Name',
            'description' => 'Description',
            'author' => 'Author',
            'homepage' => 'Plugin URI',
        ]);

        return (object) [
            'name' => $plugin['name'],
            'slug' => $this->slug,
            'version' => $release['version'],
            'author' => esc_html($plugin['author']),
            'homepage' => $plugin['homepage'],
            'last_updated' => $release['date'],
            'sections' => [
                'description' => esc_html($plugin['description']),
                'changelog' => nl2br(esc_html($release['notes'])),
            ],
            'download_link' => $release['package'],
        ];
    }

    public function select_source($source, $remote_source, $upgrader, $hook_extra)
    {
        if (is_wp_error($source) || ($hook_extra['plugin'] ?? '') !== $this->basename) {
            return $source;
        }

        global $wp_filesystem;

        if (!$wp_filesystem->is_file(trailingslashit($source) . wp_basename($this->basename))) {
            return new WP_Error('picts_builder_addons_invalid_package', __('The Builder Add-ons update package is missing its plugin file.', 'picts-tbaddon'));
        }

        $destination = trailingslashit($remote_source) . $this->slug . '/';
        if ($source === $destination) {
            return $source;
        }

        // WordPress takes the installed folder name from the unpacked source.
        if (!$wp_filesystem->move(rtrim($source, '/'), rtrim($destination, '/'))) {
            return new WP_Error('picts_builder_addons_package_move_failed', __('Could not prepare the Builder Add-ons update package.', 'picts-tbaddon'));
        }

        return $destination;
    }
}
