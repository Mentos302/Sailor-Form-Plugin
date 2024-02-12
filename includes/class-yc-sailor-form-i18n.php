<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.upwork.com/freelancers/imelnyk
 * @since      1.0.0
 *
 * @package    Yc_Sailor_Form
 * @subpackage Yc_Sailor_Form/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Yc_Sailor_Form
 * @subpackage Yc_Sailor_Form/includes
 * @author      Melnyk Ihor <igormelnyk302@gmail.com>
 */
class Yc_Sailor_Form_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'yc-sailor-form',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}