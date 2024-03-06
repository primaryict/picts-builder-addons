<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Module Name: Picts Custom Sub Menu Module
 * Description: Add a subpage menu either as a list (with drop Down) or a block style menu for sub pages of a the current page. Also has fallback.
 */

class TB_Submenu_Module extends Themify_Builder_Component_Module
{

	public static function get_module_name(): string
	{
		add_filter('themify_builder_active_vars', [__CLASS__, 'builder_active_enqueue']);
		return 'Sub Menu Module';
	}

	public static function builder_active_enqueue(array $vars): array
	{

		$url = plugin_dir_url(dirname(__FILE__)) . 'inc/assets/js/modules/module-submenu.js';
		$version = '1.0';
		$vars['addons'][$url] = $version;

		return $vars;
	}

	public static function get_module_icon(): string
	{
		return 'view-grid';
	}

	public static function get_js_css(): array
	{
		return array(
			'css' => plugin_dir_url(dirname(__FILE__)) . 'inc/assets/css/sub-menu.css'
		);
	}

	// Add the Styling JSON
	public static function get_json_file(): array
	{
		return ['f' => PICTS_TBADDON_PLUGIN_DIR . 'inc/assets/json/module-submenu.json', 'v' => '0.0.1'];
	}
}
