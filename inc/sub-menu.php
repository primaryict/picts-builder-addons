<?php

defined('ABSPATH') || exit;

function picts_tbaddon_enqueue_submenu()
{
    wp_enqueue_style('picts-sub-menu-tb-addon-css', PICTS_TBADDON_PLUGIN_DIR . 'inc/assets/css/sub-menu.css', ['dashicons'], PICTS_TBADDON_VERSION);
    wp_enqueue_script('picts-sub-menu-tb-addon', PICTS_TBADDON_PLUGIN_DIR . 'inc/assets/js/sub-menu.js', ['jquery'], PICTS_TBADDON_VERSION, true);
}
add_action('wp_enqueue_scripts', 'picts_tbaddon_enqueue_submenu');

class Picts_Builder_Addons_Block_Walker extends Walker_Page
{
    public function start_el(&$output, $data_object, $depth = 0, $args = [], $current_object_id = 0)
    {
        $title = esc_html(wp_strip_all_tags(get_the_title($data_object)));
        $image = get_the_post_thumbnail_url($data_object, 'main-featured-thumbnail') ?: ($args['walker_arg'] ?? '');
        $image = esc_url($image);
        $image_markup = $image === '' ? '' : '<img src="' . $image . '" alt="" />';

        $output .= '<li class="page_block_item"><a href="' . esc_url(get_permalink($data_object)) . '">'
            . $image_markup . '<h4>' . $title . '</h4></a>';
    }
}

function picts_tbaddon_submenu_toggle()
{
    return '<button type="button" class="dropDown" aria-expanded="false" aria-label="'
        . esc_attr__('Toggle submenu', 'picts-tbaddon')
        . '"><span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span></button>';
}

class Picts_Builder_Addons_Page_Walker extends Walker_Page
{
    public function start_el(&$output, $data_object, $depth = 0, $args = [], $current_object_id = 0)
    {
        parent::start_el($output, $data_object, $depth, $args, $current_object_id);
        if ($this->has_children) {
            $output .= picts_tbaddon_submenu_toggle();
        }
    }
}

class Picts_Builder_Addons_Menu_Walker extends Walker_Nav_Menu
{
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        parent::start_el($output, $data_object, $depth, $args, $current_object_id);
        if ($this->has_children) {
            $output .= picts_tbaddon_submenu_toggle();
        }
    }
}

function picts_tbaddon_register_menus()
{
    register_nav_menu('sidebar-fallback-menu', __('Sidebar Fallback Menu', 'picts-tbaddon'));
}
add_action('init', 'picts_tbaddon_register_menus');
