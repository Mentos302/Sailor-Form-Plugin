<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/freelancers/imelnyk
 * @since             1.0.0
 * @package           Yc_Sailor_Form
 *
 * @wordpress-plugin
 * Plugin Name:       Sailor Forms
 * Plugin URI:        https://questhowth.s-tet.top
 * Description:       This plugin will show the custom form fields based on the quantity of products purchased by client.
 * Version:           1.0.0
 * Author:            Enigma Tech
 * Author URI:        https://www.upwork.com/freelancers/imelnyk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yc-sailor-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'YC_SAILOR_FORM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-yc-sailor-form-activator.php
 */
function activate_yc_sailor_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yc-sailor-form-activator.php';
	Yc_Sailor_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-yc-sailor-form-deactivator.php
 */
function deactivate_yc_sailor_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yc-sailor-form-deactivator.php';
	Yc_Sailor_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_yc_sailor_form' );
register_deactivation_hook( __FILE__, 'deactivate_yc_sailor_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yc-sailor-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_yc_sailor_form() {

	$plugin = new Yc_Sailor_Form();
	$plugin->run();

}
run_yc_sailor_form();