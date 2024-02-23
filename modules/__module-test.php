<?php
defined('ABSPATH') || exit;

/**
 * Module Name: Pics Test
 * Description: Testing 7.5 update
 */
class TB_Test_Module extends Themify_Builder_Component_Module
{

    public static function get_module_name(): string
    {
        return __('Picts Test', 'themify');
    }

    // public static function get_module_icon(): string
    // {
    //     return 'mouse-alt';
    // }


    // public static function get_js_css(): array
    // {
    //     return array(
    //         'css' => 1
    //     );
    // }
}
