<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$fields_default = array(
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
    $pdf_file = (isset($fields_args['pdf_file'])) ? $fields_args['pdf_file'] : null;

    if ($pdf_file) { ?>

        <div class="picts_pdf_viewer_block_wrapper">

            <?php echo do_shortcode('[picts_pdfviewer pdf="' . $pdf_file . '"]'); ?>

        </div>

    <?php } ?>

    <?php do_action('themify_builder_after_template_content_render'); ?>
</div><!-- .module-<?php echo $args['mod_name']; ?> -->