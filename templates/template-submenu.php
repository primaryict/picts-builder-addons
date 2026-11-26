<?php

defined('ABSPATH') || exit;

$fields_args = wp_parse_args($args['mod_settings'] ?? [], [
    'menu_type' => 'block',
    'block_menu_icon' => '',
    'custom_menu' => '',
    'allow_menu_fallback' => '',
    'layout_menu' => '',
    'color_menu' => '',
    'according_style_menu' => '',
]);

$module_name = $args['mod_name'];
$module_id = $args['module_ID'];
$container_class = apply_filters('themify_builder_module_classes', [
    'module', 'module-' . $module_name, $module_id,
], $module_name, $module_id, $fields_args);

if (!empty($args['element_id'])) {
    $container_class[] = 'tb_' . $args['element_id'];
}
if (!empty($fields_args['css_menu'])) {
    $container_class[] = $fields_args['css_menu'];
}
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
    $container_class[] = $fields_args['global_styles'];
}

$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, [
    'id' => $module_id,
    'class' => implode(' ', $container_class),
]), $fields_args, $module_name, $module_id);
self::sticky_element_props($container_props, $fields_args);

$page_id = get_the_ID();
$list_args = ['title_li' => '', 'sort_column' => 'menu_order', 'echo' => false];
?>

<div <?php echo themify_get_element_attributes($container_props); ?>>
    <?php do_action('themify_builder_before_template_content_render'); ?>

    <?php if ($fields_args['menu_type'] === 'block') : ?>
        <?php if ($page_id) : ?>
            <div class="picts_sub_page_block_wrapper">
                <ul class="picts_sub_page_block_list"><?php
                    echo wp_list_pages($list_args + [
                        'child_of' => $page_id,
                        'depth' => 1,
                        'walker' => new Picts_Builder_Addons_Block_Walker(),
                        'walker_arg' => $fields_args['block_menu_icon'],
                    ]);
                ?></ul>
            </div>
        <?php endif; ?>
    <?php else :
        $pages = '';
        $wrapper_class = 'picts_sub_page_list_wrapper';

        if ($page_id) {
            $page_list_args = $list_args + ['walker' => new Picts_Builder_Addons_Page_Walker()];
            $pages = wp_list_pages($page_list_args + ['child_of' => $page_id]);
            if (!$pages && ($parent_id = wp_get_post_parent_id($page_id))) {
                $pages = wp_list_pages($page_list_args + ['child_of' => $parent_id]);
                $wrapper_class .= ' parentpage';
            }
        }

        if ($pages) {
            ?>
            <div class="<?php echo esc_attr($wrapper_class); ?>">
                <ul class="picts_sub_page_list"><?php echo $pages; ?></ul>
            </div>
            <?php
        } else {
            $menu = '';
            $configured_menu = !empty($fields_args['custom_menu']) ? wp_get_nav_menu_object($fields_args['custom_menu']) : false;
            if ($configured_menu) {
                $menu = wp_nav_menu([
                    'menu' => $configured_menu,
                    'menu_class' => 'picts_sub_page_list menuList',
                    'container_class' => 'picts_sub_page_list_wrapper',
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                    'fallback_cb' => false,
                    'echo' => false,
                    'walker' => new Picts_Builder_Addons_Menu_Walker(),
                ]);
            }

            if ($menu) {
                echo $menu;
            } elseif (!empty($fields_args['allow_menu_fallback'])) {
                printf(
                    '<div class="module-menu-container"><ul class="%1$s">%2$s</ul></div>',
                    esc_attr('ui tf_clearfix nav tf_rel ' . $fields_args['layout_menu'] . ' ' . $fields_args['color_menu'] . ' ' . $fields_args['according_style_menu']),
                    wp_list_pages($list_args)
                );
            }
        }
    endif; ?>

    <?php do_action('themify_builder_after_template_content_render'); ?>
</div>
