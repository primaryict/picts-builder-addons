<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Picts Extended Button
 * Description: Display Button content
 */
class TB_Addfile_Module extends Themify_Builder_Component_Module
{

    public static function get_module_name(): string
    {
        add_filter('themify_builder_active_vars', [__CLASS__, 'builder_active_enqueue']);
        return 'Add File';
    }

    public static function builder_active_enqueue(array $vars): array
    {

        $url = plugin_dir_url(dirname(__FILE__)) . 'inc/assets/js/modules/module-addfile.js';
        $version = '1.0';
        $vars['addons'][$url] = $version;

        return $vars;
    }

    public static function get_module_icon(): string
    {
        return 'mouse-alt';
    }

    public static function get_js_css(): array
    {
        return array(
            'css' => plugin_dir_url(dirname(__FILE__)) . 'inc/assets/css/module-addfile.css'
        );
    }

    // Add the Styling JSON
    public static function get_json_file(): array
    {
        return ['f' => PICTS_TBADDON_PLUGIN_DIR . 'inc/assets/json/module-addfile.json', 'v' => '0.0.1'];
    }
}
