<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class PICTS_Subpage_Module extends Themify_Builder_Component_Module
{

	function __construct()
	{
		parent::__construct(array(
			'name' => __('Sub Menu', 'sub-menu'),
			'slug' => 'submenu',
			'category' => array('addon')
		));
	}

	public function get_icon()
	{
		return 'view-grid';
	}

	/**
	 * Module options.
	 *
	 * @return array
	 */
	public function get_options()
	{
		return array(
			array(
				'id' => 'menu_type',
				'type' => 'radio',
				'label' => __('Sub Menu Type', 'sub-menu'),
				'options' => array(
					array('value' => 'block', 'name' => __('Block', 'sub-menu')),
					array('value' => 'list', 'name' => __('List', 'sub-menu'))
				),
				'option_js' => true
			),
			array(
				'id' => 'block_menu_icon',
				'type' => 'image',
				'label' => __('Block Menu Icon', 'sub-menu')
			),
			array(
				'id' => 'custom_menu',
				'type' => 'select',
				'dataset' => 'menu',
				'description' => sprintf(__('Add/edit <a href="%s" target="_blank">%s</a>', 'themify'), admin_url('nav-menus.php'), __('Menus', 'themify')),
				'label' => __('Fallback Menu', 'themify'),
				'options' => array()
			),
			array(
				'id' => 'allow_menu_fallback',
				'label' => '',
				'type' => 'checkbox',
				'options' => array(
					array('name' => 'allow_fallback', 'value' => __('List all pages as fallback', 'themify'))
				)
			),
			array('type' => 'custom_css_id')
		);
	}

	/**
	 * Module Styling settings
	 *
	 * @return array
	 */
	public function get_styling()
	{

		$general = array(
			//background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('', 'background_color', 'bg_c', 'background-color'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(),
							self::get_color('', 'font_color'),
							self::get_font_size(),
							self::get_line_height(),
							self::get_text_align()
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f', 'h'),
							self::get_color('', 'f_c', null, null, 'h'),
							self::get_font_size('', 'f_s', '', 'h'),
							self::get_line_height('', 'l_h', 'h'),
							self::get_text_align('', 't_a', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding()
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin()
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'm', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border()
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'b', 'h')
						)
					)
				))
			))
		);

		$menu_blocks = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_image(array(' .page_block_item'), 'bg_i', 'background-color', 'bg_r', 'bg_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image(array(' .page_block_item:hover'), 'bg_i', 'background-color', 'b_c_t', 'bg_r', 'bg_p', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .page_block_item', 'b_a_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .page_block_item:hover', 'b_a_t', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .page_block_item a', 'p_a_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .page_block_item a:hover', 'p_a_t', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .page_block_item', 'r_c_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .page_block_item:hover', 'r_c_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .page_block_item'), 'sh_block')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .page_block_item:hover'), 'sh_block', 'h')
						)
					)
				))
			))
		);

		$menu_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_image(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li'), 'bg_i', 'background_color_title', 'bg_r', 'bg_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li:hover'), 'bg_i', 'b_c_t', 'bg_r', 'bg_p', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 'font_family_title'),
							self::get_color(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 'font_color_title'),
							self::get_font_size(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 'font_size_title'),
							self::get_line_height(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 'line_height_title'),
							self::get_letter_spacing(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 'l_s_t'),
							self::get_text_transform(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 't_t_t'),
							self::get_font_style(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 'f_s_t', 'f_t_b'),
							self::get_text_decoration(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 't_d_t'),
							self::get_text_shadow(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list li a'), 't_sh_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 'f_f_t', 'h'),
							self::get_color(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 'f_c_t', null, null, ''),
							self::get_font_size(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 'f_s_t', '', 'h'),
							self::get_line_height(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 'l_h_t', 'h'),
							self::get_letter_spacing(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 'l_s_t', 'h'),
							self::get_text_transform(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 't_t_t', 'h'),
							self::get_font_style(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 'f_st_t', 'f_t_b', 'h'),
							self::get_text_decoration(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 't_d_t', 'h'),
							self::get_text_shadow(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list li a:hover'), 't_sh_t', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list > li'), 'p_a_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list > li:hover'), 'p_a_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .page_block_item a h4', ' .picts_sub_page_list_wrapper a.title', ' .picts_sub_page_list > li'), 'sh_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .page_block_item a h4:hover', ' .picts_sub_page_list_wrapper a.title:hover', ' .picts_sub_page_list > li:hover'), 'sh_t', 'h')
						)
					)
				))
			))
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'ct' => array(
					'label' => __('Menu Block', 'themify'),
					'options' => $menu_blocks
				),
				't' => array(
					'label' => __('Menu Title', 'themify'),
					'options' => $menu_title
				),
			),
		);
	}
}

Themify_Builder_Model::register_module('PICTS_Subpage_Module');
