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
				'id' => 'fallback_image',
				'type' => 'image',
				'label' => __('Fallback Image', 'sub-menu')
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
		return array(
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
	}

	/**
	 * Outputs the visual template, used in frontend editor for preview
	 *
	 * @return void
	 */
	protected function _visual_template()
	{
?>
		<div class="module module-<?php echo $this->slug; ?>">
			<p>Preview not available</p>
		</div>
<?php
	}
}

Themify_Builder_Model::register_module('PICTS_Subpage_Module');
