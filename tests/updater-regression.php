<?php
error_reporting(E_ALL);
set_error_handler(static function ($severity, $message, $file, $line) { throw new ErrorException($message, 0, $severity, $file, $line); });
$GLOBALS['hooks'] = [];
function add_filter($name, $callback, $priority = 10, $accepted = 1) { $GLOBALS['hooks'][$name][$priority][] = [$callback, $accepted]; }
function add_action($name, $callback, $priority = 10, $accepted = 1) { add_filter($name, $callback, $priority, $accepted); }
function remove_filter($name, $callback, $priority = 10) { foreach ($GLOBALS['hooks'][$name][$priority] ?? [] as $i => $entry) { if ($entry[0] === $callback) unset($GLOBALS['hooks'][$name][$priority][$i]); } }
function apply_filters($name, $value, ...$args) { $groups = $GLOBALS['hooks'][$name] ?? []; ksort($groups); foreach ($groups as $entries) foreach ($entries as [$callback, $count]) $value = $callback(...array_slice([$value, ...$args], 0, $count)); return $value; }
function wp_parse_url($url) { return parse_url($url); }
function plugin_basename($file) { return 'picts-builder-addons/builder-addons.php'; }
function plugin_dir_path($file) { return '/plugins/picts-builder-addons/'; }
function trailingslashit($value) { return rtrim($value, '/') . '/'; }
function untrailingslashit($value) { return rtrim($value, '/'); }
function get_plugin_data($file) { return ['PluginURI'=>'https://example.test/', 'Name'=>'Builder Add-ons', 'AuthorName'=>'Primary ICT', 'AuthorURI'=>'https://example.test/', 'Description'=>'Example']; }
function is_plugin_active($plugin) { return true; }
function activate_plugin($plugin) { $GLOBALS['activations'][] = $plugin; }
function is_wp_error($value) { return $value instanceof WP_Error; }
function wp_remote_get($url, $args = []) { return ['body' => json_encode([['tag_name'=>'0.1.5', 'zipball_url'=>'https://api.github.com/repos/primaryict/picts-builder-addons/zipball/0.1.5']])]; }
function wp_remote_retrieve_body($result) { return $result['body']; }
class WP_Error { public function __construct(public $code = '', public $message = '') {} }
class FakeFilesystem { public $moves = []; public $ok = true; public function exists($path) { return str_ends_with($path, '/builder-addons.php'); } public function move($from, $to, $overwrite = false) { $this->moves[] = [$from, $to, $overwrite]; return $this->ok; } }
$GLOBALS['wp_filesystem'] = new FakeFilesystem();
require __DIR__ . '/../inc/updater/updater.php';
$class = class_exists('PICTS_Builder_Addons_Updater') ? 'PICTS_Builder_Addons_Updater' : 'Picts_Updater';
$updater = new $class('/plugins/picts-builder-addons/builder-addons.php');
$updater->set_username('primaryict'); $updater->set_repository('picts-builder-addons'); $updater->authorize('test-secret'); $updater->initialize();
$tests = [];
$tests['unrelated downloads preserve earlier result and add no HTTP filter'] = static function () { $prior = new WP_Error('earlier'); $reply = apply_filters('upgrader_pre_download', $prior, 'https://vendor.example/plugin.zip', null, ['plugin'=>'vendor/main.php']); if ($reply !== $prior) throw new Exception('earlier result was overwritten'); if (!empty($GLOBALS['hooks']['http_request_args'])) throw new Exception('HTTP hook registered for unrelated download'); };
$tests['unrelated post-install preserves result and does not move files'] = static function () { $fs=$GLOBALS['wp_filesystem']; $fs->moves=[]; $reply=apply_filters('upgrader_post_install', true, ['plugin'=>'vendor/main.php'], ['destination'=>'/plugins/vendor/']); if ($reply !== true || $fs->moves) throw new Exception('unrelated install was altered'); };
$tests['earlier post-install errors remain unchanged'] = static function () { $prior=new WP_Error('earlier'); $reply=apply_filters('upgrader_post_install', $prior, ['plugin'=>'picts-builder-addons/builder-addons.php'], ['destination'=>'/plugins/temporary/']); if ($reply !== $prior) throw new Exception('earlier error was overwritten'); };
$tests['own archive is renamed safely before install'] = static function () { $fs=$GLOBALS['wp_filesystem']; $fs->moves=[]; $fs->ok=true; $reply=apply_filters('upgrader_source_selection', '/tmp/unpacked/revision/', '/tmp/unpacked/', null, ['plugin'=>'picts-builder-addons/builder-addons.php']); if ($reply !== '/tmp/unpacked/picts-builder-addons/' || $fs->moves !== [['/tmp/unpacked/revision/','/tmp/unpacked/picts-builder-addons',false]]) throw new Exception('own source folder was not prepared safely: ' . var_export([$reply, $fs->moves], true)); };
$tests['failed source move returns error'] = static function () { $fs=$GLOBALS['wp_filesystem']; $fs->ok=false; $reply=apply_filters('upgrader_source_selection', '/tmp/unpacked/revision/', '/tmp/unpacked/', null, ['plugin'=>'picts-builder-addons/builder-addons.php']); $fs->ok=true; if (!is_wp_error($reply)) throw new Exception('failed move was treated as success'); };
$tests['unrelated extracted source remains unchanged'] = static function () { $fs=$GLOBALS['wp_filesystem']; $fs->moves=[]; $reply=apply_filters('upgrader_source_selection', '/tmp/unpacked/vendor/', '/tmp/unpacked/', null, ['plugin'=>'vendor/main.php']); if ($reply !== '/tmp/unpacked/vendor/' || $fs->moves) throw new Exception('unrelated source was altered'); };
$tests['background update check handles missing admin initialisation'] = static function () { $in=(object)['checked'=>['picts-builder-addons/builder-addons.php'=>'0.1.4'], 'response'=>[], 'no_update'=>['picts-builder-addons/builder-addons.php'=>(object)[]]]; $out=apply_filters('pre_set_site_transient_update_plugins', $in); if (empty($out->response['picts-builder-addons/builder-addons.php'])) throw new Exception('own update was not offered in background'); if (isset($out->no_update['picts-builder-addons/builder-addons.php'])) throw new Exception('stale no-update entry remains'); };
$tests['own authentication never leaks to unrelated URLs'] = static function () { $package='https://api.github.com/repos/primaryict/picts-builder-addons/zipball/0.1.5'; $reply=apply_filters('upgrader_pre_download', false, $package, null, ['plugin'=>'picts-builder-addons/builder-addons.php']); if ($reply !== false) throw new Exception('own download altered'); $args=['headers'=>[],'redirection'=>5]; $other=apply_filters('http_request_args', $args, 'https://other.example/plugin.zip'); if ($other !== $args) throw new Exception('credentials leaked to unrelated request'); $own=apply_filters('http_request_args', $args, $package); if (($own['headers']['Authorization'] ?? '') !== 'token test-secret' || $own['redirection'] !== 0) throw new Exception('own token was not scoped or redirects are enabled'); $after=apply_filters('http_request_args', $args, $package); if ($after !== $args) throw new Exception('temporary HTTP filter remained active'); };
$tests['non-GitHub package does not receive authentication'] = static function () { $package='https://api.github.com.evil.example/repos/primaryict/picts-builder-addons/zipball/0.1.5'; $args=['headers'=>[]]; apply_filters('upgrader_pre_download', false, $package, null, ['plugin'=>'picts-builder-addons/builder-addons.php']); if (apply_filters('http_request_args', $args, $package) !== $args) throw new Exception('untrusted host received credentials'); };
$tests['class is isolated from sibling plugins'] = static function () { if (!class_exists('PICTS_Builder_Addons_Updater')) throw new Exception('builder add-ons still uses shared global updater class'); };
$failures=0; foreach ($tests as $name=>$test) { try { $test(); echo "PASS $name\n"; } catch (Throwable $e) { $failures++; echo "FAIL $name: {$e->getMessage()}\n"; } } exit($failures ? 1 : 0);
