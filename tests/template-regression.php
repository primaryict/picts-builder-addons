<?php
error_reporting(E_ALL);
set_error_handler(static function ($severity, $message, $file, $line) { throw new ErrorException($message, 0, $severity, $file, $line); });
define('ABSPATH', '/wordpress/');
function wp_parse_args($args, $defaults) { return array_merge($defaults, $args); }
function apply_filters($name, $value, ...$args) { return $value; }
function do_action(...$args) {}
function wp_get_post_parent_id($id) { return 0; }
function wp_list_pages($args) { $GLOBALS['page_calls'][]=$args; return ''; }
function wp_nav_menu($args) { return ''; }
class Themify_Builder { public static $frontedit_active=false; }
class ChildpageBlocksWalker {}
class TemplateHarness {
 public static function parse_animation_effect($v) { return ''; }
 public static function get_element_attributes($v) { return ''; }
 public static function sticky_element_props($props, $fields) { return $props; }
 public static function render($type) { global $post; $args=['mod_settings'=>['menu_type'=>$type], 'mod_name'=>'submenu', 'module_ID'=>'m1']; ob_start(); try { include __DIR__ . '/../templates/template-submenu.php'; } finally { ob_end_clean(); } }
}
$failures=0;
foreach (['block','list'] as $type) { $GLOBALS['post']=null; $GLOBALS['page_calls']=[]; try { TemplateHarness::render($type); if ($GLOBALS['page_calls']) throw new Exception('queried unrelated pages with no current post'); echo "PASS null post in $type template\n"; } catch (Throwable $e) { $failures++; echo "FAIL null post in $type template: {$e->getMessage()}\n"; } }
exit($failures ? 1 : 0);
