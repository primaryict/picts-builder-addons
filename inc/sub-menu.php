<?php
/*
Addon Name:  PICTS Custom Sub Page Menu
Description:  Dynamic sub menu on side bar based on the page structure
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Load plugin's stylesheet on frontend
 */
function sub_menu_tb_enqueue()
{
    wp_enqueue_style('sub-menu-tb-addon', PICTS_TBADDON_PLUGIN_URI . '/inc/assets/style.css');
}
add_action('wp_enqueue_scripts', 'sub_menu_tb_enqueue');


/**
 * Register module to Themify Builder
 *
 * @uses "themify_builder_setup_modules" hook
 */
class ChildpageBlocksWalker extends Walker_Page
{


    function start_el(&$output, $page, $depth = 0, $args = array(), $id = 0)
    {

        $fallback_image = (isset($args['walker_arg'])) ? esc_attr($args['walker_arg']) : '';

        if (has_post_thumbnail($page->ID)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($page->ID), 'main-featured-thumbnail');
            $imageCap = get_post(get_post_thumbnail_id($page->ID))->post_excerpt;
            $link_title = '<img src="' . $image[0] . '" alt="' . esc_attr(wp_strip_all_tags(apply_filters('the_title', $page->post_title, $page->ID))) . '"/><h4>' . esc_attr(wp_strip_all_tags(apply_filters('the_title', $page->post_title, $page->ID))) . '</h4>';
        } elseif ($fallback_image) {
            $link_title = '<img src="' . $fallback_image . '" alt="' . esc_attr(wp_strip_all_tags(apply_filters('the_title', $page->post_title, $page->ID))) . '"/><h4>' . esc_attr(wp_strip_all_tags(apply_filters('the_title', $page->post_title, $page->ID))) . '</h4>';
        } else {
            $link_title = '<h4>' . esc_attr(wp_strip_all_tags(apply_filters('the_title', $page->post_title, $page->ID))) . '</h4>';
        }
        $output .= '<li class="page_block_item"><a href="' . get_permalink($page->ID) . '">' . $link_title . '</a></li>';
    }
}
