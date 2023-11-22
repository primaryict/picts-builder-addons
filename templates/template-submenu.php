<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Template Quotes
 *
 * Access original fields: $args['mod_settings']
 */
global $post;

$fields_default = array(
	'quote_text' => '',
	'quote_image' => '',
	'quote_author' => '',
	'quote_link' => '',
	'animation_effect' => ''
);

$fields_args = wp_parse_args($args['mod_settings'], $fields_default);
unset($args['mod_settings']);

$container_class = apply_filters('themify_builder_module_classes', array(
	'module', 'module-' . $args['mod_name'], $args['module_ID'], self::parse_animation_effect($fields_args['animation_effect'])
), $args['mod_name'], $args['module_ID'], $fields_args);

if (!empty($args['element_id'])) {
	$container_class[] = 'tb_' . $args['element_id'];
}

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}

$container_props = apply_filters('themify_builder_module_container_props', array(
	'id' => $args['module_ID'],
	'class' => implode(' ', $container_class),
), $fields_args, $args['mod_name'], $args['module_ID']);
?>

<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php $container_props = $container_class = null; ?>
	<?php do_action('themify_builder_before_template_content_render'); ?>

	<?php

	$menu_type = (isset($fields_args['menu_type'])) ? $fields_args['menu_type'] : 'block';
	$block_menu_icon = (isset($fields_args['block_menu_icon'])) ? $fields_args['block_menu_icon'] : null;
	$custom_menu = (isset($fields_args['custom_menu'])) ? $fields_args['custom_menu'] : null;
	$allow_menu_fallback = (isset($fields_args['allow_menu_fallback'])) ? $fields_args['allow_menu_fallback'] : null;

	if ($menu_type == "block") { ?>

		<div class="quote-picts_sub_page_block_wrapper">
			<ul class="picts_sub_page_block_list">

				<?php wp_list_pages(array(
					'title_li'      => '',
					'child_of'      => $post->ID,
					'sort_column'   => 'menu_order',
					'depth'         => 1,
					'walker'        => new ChildpageBlocksWalker,
					'walker_arg'    => $block_menu_icon,

				)); ?>

			</ul>
		</div>

		<?php } else {

		$link_after = '<a href="#" class="dropDown"><span class="dashicons dashicons-arrow-down-alt2"></span></i></a>';

		$parentpage_id = wp_get_post_parent_id($post->ID);


		$childpages = wp_list_pages('sort_column=menu_order&title_li=&link_after=' . $link_after . '&child_of=' . $post->ID . '&echo=0');
		$parentpages = wp_list_pages('sort_column=menu_order&title_li=&link_after=' . $link_after . '&child_of=' . $parentpage_id . '&echo=0');


		if ($childpages) { ?>

			<div class="picts_sub_page_list_wrapper">
				<ul class="picts_sub_page_list"><?php echo $childpages; ?></ul>
			</div>

		<?php } elseif ($parentpages && $parentpage_id !== 0) { ?>
			<div class="picts_sub_page_list_wrapper parentpage">
				<ul class="picts_sub_page_list"><?php echo $parentpages; ?></ul>
			</div>
		<?php } elseif ($custom_menu) { ?>

	<?php
			echo wp_nav_menu(
				array(
					'menu'		  	  => $custom_menu,
					'menu_class'      => 'menu-wrapper',
					'container_class' => 'picts_sub_page_list_wrapper',
					'items_wrap'      => '<ul class="picts_sub_page_list menuList" class="%2$s">%3$s</ul>',
					'fallback_cb'     => false,
					'link_after'      => $link_after
				)
			);
		} elseif (!empty($allow_menu_fallback)) {
			$args = array(
				'title_li'	=> '',
				'echo'		=> 0,
			);
			printf(
				'<div class="module-menu-container"><ul class="%1$s">%2$s</ul></div>',
				'ui tf_clearfix nav tf_rel ' . $fields_args['layout_menu'] . ' ' . $fields_args['color_menu'] . ' ' . $fields_args['according_style_menu'],
				wp_list_pages($args)
			);
		}
	} ?>



	<?php do_action('themify_builder_after_template_content_render'); ?>
</div><!-- .module-<?php echo $args['mod_name']; ?> -->