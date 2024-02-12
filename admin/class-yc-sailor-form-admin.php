<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.upwork.com/freelancers/imelnyk
 * @since      1.0.0
 *
 * @package    Yc_Sailor_Form
 * @subpackage Yc_Sailor_Form/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yc_Sailor_Form
 * @subpackage Yc_Sailor_Form/admin
 * @author     Melnyk Ihor <igormelnyk302@gmail.com>
 */
class Yc_Sailor_Form_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yc_Sailor_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yc_Sailor_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yc-sailor-form-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yc_Sailor_Form_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yc_Sailor_Form_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yc-sailor-form-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function register_meta_boxes() {
		add_meta_box( 'yc-sailor-metabox-id', esc_html__( 'Sailor', '' ), array( $this, 'yc_sailor_meta_box_callback' ), 'shop_order', 'advanced', 'high' );
	}
	public function yc_sailor_meta_box_callback( $post ) {
		$order = wc_get_order( $post->ID );
		require( 'partials/yc-sailor-metabox.php' );
	}

}