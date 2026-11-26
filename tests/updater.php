<?php

// Run with: php tests/updater.php
if (PHP_SAPI !== 'cli') {
    exit;
}

define('ABSPATH', __DIR__ . '/');
define('MINUTE_IN_SECONDS', 60);

set_error_handler(static function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

function check($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function plugin_basename($file) { return 'picts-builder-addons/builder-addons.php'; }
function add_action(...$args) {}
function add_filter(...$args) {}
function get_plugin_data($file) { return ['Version' => '0.1.4', 'PluginURI' => 'https://example.org']; }
function is_plugin_active($file) { return true; }
function get_file_data($file, $headers) { return array_fill_keys(array_keys($headers), 'Example'); }
function get_site_transient($key) { return $GLOBALS['cache'][$key] ?? false; }
function set_site_transient($key, $value, $expiration) { $GLOBALS['cache'][$key] = $value; }
class WP_Error {}
function is_wp_error($value) { return $value instanceof WP_Error; }
function wp_remote_get($url, $args = []) { ++$GLOBALS['requests']; return $GLOBALS['http']; }
function wp_remote_retrieve_body($response) { return $response['body'] ?? ''; }
function wp_remote_retrieve_response_code($response) { return $response['response']['code'] ?? ''; }
function trailingslashit($path) { return rtrim($path, '/') . '/'; }
function wp_basename($path) { return basename($path); }
function __($text, $domain = '') { return $text; }
function esc_html($text) { return htmlspecialchars($text, ENT_QUOTES); }

require ($argv[1] ?? dirname(__DIR__)) . '/inc/updater/updater.php';

function updater()
{
    $GLOBALS['cache'] = [];
    $GLOBALS['requests'] = 0;
    if (class_exists('Picts_Builder_Addons_Updater')) {
        return new Picts_Builder_Addons_Updater('/plugins/picts-builder-addons/builder-addons.php');
    }
    $updater = new Picts_Updater('/plugins/picts-builder-addons/builder-addons.php');
    $updater->set_username('primaryict');
    $updater->set_repository('picts-builder-addons');
    $updater->set_plugin_properties();
    return $updater;
}

$plugin = 'picts-builder-addons/builder-addons.php';
$transient = static function ($version = '0.1.4') use ($plugin) {
    return (object) ['checked' => [$plugin => $version], 'response' => ['other/plugin.php' => (object) []]];
};
$info = (object) ['slug' => 'picts-builder-addons'];
foreach ([
    [200, '["temporarily unavailable"]'],
    [200, '"temporarily unavailable"'],
    [200, '[]'],
    [200, '<html>Bad gateway</html>'],
    [200, '{"tag_name":"0.2.0"}'],
    [200, '{"tag_name":"0.2.0","zipball_url":"https://example.org/package.zip"}'],
    [200, '{"tag_name":"0.2.0","prerelease":true,"zipball_url":"https://api.github.com/repos/primaryict/picts-builder-addons/zipball/0.2.0"}'],
    [403, '{"message":"rate limit exceeded"}'],
    [404, '{"message":"Not Found"}'],
    new WP_Error(),
] as $response) {
    $GLOBALS['http'] = is_array($response)
        ? ['response' => ['code' => $response[0]], 'body' => $response[1]]
        : $response;
    $updater = updater();
    $result = $updater->modify_transient($transient());
    check(array_keys($result->response) === ['other/plugin.php'], 'Invalid response changed the update list');
    check($updater->plugin_popup(false, 'plugin_information', $info) === false, 'Invalid response populated plugin details');
    check($GLOBALS['requests'] === 1, 'Invalid response triggered repeated requests');
}

$updater = updater();
check($updater->modify_transient(false) === false, 'Invalid transient changed');
$other = (object) ['checked' => ['other/plugin.php' => '1.0']];
check($updater->modify_transient($other) === $other && $GLOBALS['requests'] === 0, 'Unrelated update triggered a GitHub request');
check($updater->plugin_popup(false, 'query_plugins', $info) === false && $GLOBALS['requests'] === 0, 'Plugin search triggered a GitHub request');

$package = 'https://api.github.com/repos/primaryict/picts-builder-addons/zipball/v0.2.0';
$GLOBALS['http'] = ['response' => ['code' => 200], 'body' => json_encode([
    'tag_name' => 'v0.2.0', 'zipball_url' => $package, 'body' => '<b>Notes</b>',
])];
$update = $updater->modify_transient($transient())->response[$plugin];
check($update->package === $package && $update->new_version === '0.2.0' && $update->plugin === $plugin, 'Valid release missing update fields');
check(!isset($updater->modify_transient($transient('0.2.0'))->response[$plugin]), 'Current version was offered as an update');
check(!isset($updater->modify_transient($transient('0.3.0'))->response[$plugin]), 'Older release was offered as an update');
check($updater->plugin_popup(false, 'plugin_information', $info)->version === '0.2.0', 'Valid release missing from plugin details');
check($GLOBALS['requests'] === 1, 'Valid metadata was not reused');

$wp_filesystem = new class {
    public $file_exists = true;
    public $can_move = true;
    public $moves = 0;
    public function is_file($path) { return $this->file_exists; }
    public function move($source, $destination) { ++$this->moves; return $this->can_move; }
};
$source = '/upgrade/temp/primaryict-picts-builder-addons-hash/';
$directory = '/upgrade/temp/';
$extra = ['plugin' => $plugin];
check($updater->select_source($source, $directory, null, ['plugin' => 'other/plugin.php']) === $source && !$wp_filesystem->moves, 'Unrelated package was moved');
check($updater->select_source($source, $directory, null, $extra) === $directory . 'picts-builder-addons/', 'GitHub archive did not use the plugin directory');
check($updater->select_source($directory . 'picts-builder-addons/', $directory, null, $extra) === $directory . 'picts-builder-addons/' && $wp_filesystem->moves === 1, 'Canonical ZIP was moved unnecessarily');
$wp_filesystem->file_exists = false;
check(is_wp_error($updater->select_source($source, $directory, null, $extra)) && $wp_filesystem->moves === 1, 'Invalid archive proceeded to install');
$wp_filesystem->file_exists = true;
$wp_filesystem->can_move = false;
check(is_wp_error($updater->select_source($source, $directory, null, $extra)), 'Failed archive move proceeded to install');

echo "Updater regression checks passed.\n";
