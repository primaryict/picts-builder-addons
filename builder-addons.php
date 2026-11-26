<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Primary ICT Support - Themify Builder Add-ons
 * Plugin URI:        https://www.primaryictsupport.co.uk/
 * Description:       Adds Sub Menu and Add File modules to Themify Builder.
 * Text Domain:       picts-tbaddon
 * Version:           0.1.5
 * Update URI:        https://github.com/primaryict/picts-builder-addons
 * Author:            John Emmett
 * Author URI:        https://www.primaryictsupport.co.uk/
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
define('PICTS_TBADDON_VERSION', '0.1.5');

/**
 * Loading Classes.
 **/

require_once __DIR__ . '/inc/updater/updater.php';
(new Picts_Builder_Addons_Updater(__FILE__))->initialize();


// TB7.5 - Register Modules
function picts_tbaddon_register_module()
{
    Themify_Builder_Model::add_module(__DIR__ . '/modules/module-submenu.php');
    Themify_Builder_Model::add_module(__DIR__ . '/modules/module-addfile.php');
}
add_action('themify_builder_setup_modules', 'picts_tbaddon_register_module');


// Sub Menu Addon File
include_once(PICTS_TBADDON_PLUGIN_URI . 'inc/sub-menu.php');


// TB7.5 - Include i18n File for builder lables
function picts_add_i18n($vars)
{
    $i18n = include(PICTS_TBADDON_PLUGIN_URI . 'inc/i18n/i18n.php');
    $vars['i18n']['label'] = array_merge($i18n, $vars['i18n']['label'] ?? []);

    return $vars;
}
add_filter('themify_builder_active_vars', 'picts_add_i18n', 10, 1);
