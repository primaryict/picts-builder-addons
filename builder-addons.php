<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Primary ICT Support - Themify Builder Add-ons
 * Plugin URI:        www.primaryictsupport.co.uk
 * Description:       Primary ICT Support plugin to add additional features to the Themify builder. Sub & Side Menu Module, PDF Viewer Module.
 * Version:           0.0.9
 * Author:            John Emmett
 * Author URI:        www.primaryictsupport.co.uk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Set up plugin constants
define('PICTS_TBADDON_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('PICTS_TBADDON_PLUGIN_URI', plugin_dir_path(__FILE__));
define('PICTS_TBADDON_BASENAME', plugin_basename(__FILE__));

/**
 * Loading Classes.
 **/

// GIT HUB Updater
if (!class_exists('Picts_Updater')) {
    include_once(plugin_dir_path(__FILE__) . 'inc/updater/updater.php');
}
$updater = new Picts_Updater(__FILE__);
$updater->set_username('primaryict');
$updater->set_repository('picts-builder-addons');
$updater->initialize();


function picts_register_module()
{
    Themify_Builder_Model::register_directory('templates', PICTS_TBADDON_PLUGIN_URI . '/templates');
    Themify_Builder_Model::register_directory('modules', PICTS_TBADDON_PLUGIN_URI . '/modules');
}
add_action('themify_builder_setup_modules', 'picts_register_module');


// Sub Menu Addon File
include_once(PICTS_TBADDON_PLUGIN_URI . 'inc/sub-menu.php');

// Events Shortcode
// include_once(PICTS_TBADDON_PLUGIN_URI . 'inc/shortcode/events.php');

// Add Picts PDF Viewer is it doesn't already exist
// if (!class_exists('PictsPDFViewer')) {
//     include_once(plugin_dir_path(__FILE__) . 'inc/PictsPDFViewer.php');
// }
