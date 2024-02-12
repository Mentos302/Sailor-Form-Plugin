<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.upwork.com/freelancers/imelnyk
 * @since      1.0.0
 *
 * @package    Yc_Sailor_Form
 * @subpackage Yc_Sailor_Form/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Yc_Sailor_Form
 * @subpackage Yc_Sailor_Form/public
 * @author     Melnyk Ihor <igormelnyk302@gmail.com>
 */
class Yc_Sailor_Form_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yc-sailor-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yc-sailor-form-public.js', array( 'jquery' ), $this->version, false );

	}
	/**
	 * Okay, so this is callback to hook which registers the tab on checkout page. Each tab/step name is unique as comination of
	 * of product id and quantity.
	 */
	public function wpmc_add_sailor_time_step( $steps ) {
		$show_step = false;
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		if ( count( $items ) > 0 ) {
			foreach ( $items as $item => $values ) {
				$product = $values['data'];
				$pid = $product->get_id();
				if ( ! has_term( 'merchandise', 'product_cat', $pid ) ) {
					$show_step = true;
				}
			}
			if ( $show_step ) {
				$steps['yc_sailors_form'] = array(
					'title' => __( 'Sailors Form' ),
					'position' => 25,
					'class' => 'yc_sailors_form',
					'sections' => array( 'yc_sailors_form' ),
				);
			}
			return $steps;
		}
	}

	/**
	 * Okay, so to populate the content of each tab/step we have added unique hooks based on each step key/name.
	 */
	function yc_multiple_sailor_forms() {
		if ( ! is_admin() && ! WC()->cart->is_empty() ) {
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			if ( count( $items ) > 0 ) {
				foreach ( $items as $item => $values ) {
					$product = $values['data'];
					$pid = $product->get_id();
					$counter = 1;
					while ( $counter <= $values['quantity'] ) {
						add_action( 'wmsc_step_content_yc_spid' . $pid . '_epid_sailor_' . $counter, array( $this, 'wmsc_step_content_sailor' ) );
						$counter++;
					}
				}
			}
		}
	}


	public function yc_get_string_between( $string, $start, $end ) {
		$string = ' ' . $string;
		$ini = strpos( $string, $start );
		if ( $ini == 0 )
			return '';
		$ini += strlen( $start );
		$len = strpos( $string, $end, $ini ) - $ini;
		return substr( $string, $ini, $len );
	}

	/**
	 * Okay, Now callbacks of unique hook is here. As each step key had to be unique to contain seperate forms for each quantity so
	 * this callback will called multiple times based on total qty in cart
	 */
	function wmsc_step_content_sailor() {
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		if ( count( $items ) > 0 ) {
			foreach ( $items as $item => $values ) {
				$product = $values['data'];
				$pid = $product->get_id();
				if ( has_term( 'merchandise', 'product_cat', $pid ) ) {
					continue;
				}
				$counter = 1;
				while ( $counter <= $values['quantity'] ) {
					echo '<h5 class="form-row form-row-wide">Sailor Form Number ' . $counter . ' For ' . $product->get_name() . '</h5>';
					$this->yc_create_sailors_form_field( $pid, $counter );
					$counter++;
				}
			}
		}
	}
	public function yc_create_sailors_form_field( $product_id, $quantity ) {
		$checkout = WC()->checkout();
		woocommerce_form_field( 'yc_firstname_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'text',
			'required' => true,
			'class' => array( 'input-text form-row-first' ),
			'label' => __( 'Sailor First Name' ),
		), $checkout->get_value( 'yc_firstname_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_lastname_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'text',
			'required' => true,
			'class' => array( 'input-text form-row-last' ),
			'label' => __( 'Sailor Last Name' ),
		), $checkout->get_value( 'yc_lastname_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_dob_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'date',
			'required' => true,
			'class' => array( 'input-text form-row-first' ),
			'label' => __( 'Sailor Date of Birth' ),
		), $checkout->get_value( 'yc_dob_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_allergy_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'textarea',
			'required' => true,
			'class' => array( 'input-text form-row-last yc_allergy_textbox' ),
			'label' => __( 'Sailor Allergies/Medical Conditions' ),
		), $checkout->get_value( 'yc_allergy_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_guardianfirst_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'text',
			'required' => true,
			'class' => array( 'input-text form-row-first' ),
			'label' => __( 'Parent / Guardian First Name' ),
		), $checkout->get_value( 'yc_guardianfirst_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_guardianlast_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'text',
			'required' => true,
			'class' => array( 'input-text form-row-last' ),
			'label' => __( 'Parent / Guardian Last Name' ),
		), $checkout->get_value( 'yc_guardianlast_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_guardiantel_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'text',
			'required' => true,
			'class' => array( 'input-text form-row-first' ),
			'label' => __( 'Parent / Guardian Contact phone' ),
		), $checkout->get_value( 'yc_guardiantel_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_guardianmail_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'text',
			'required' => true,
			'class' => array( 'input-text form-row-last' ),
			'label' => __( 'Parent / Guardian Contact email' ),
		), $checkout->get_value( 'yc_guardianmail_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_permissionvideo_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'radio',
			'required' => true,
			'class' => array( 'input-radio form-row-first yc_custom_radio_checkbox' ),
			'label' => __( 'Permission to be videoed/ photographed' ),
			'options' => array( 'NO' => 'No', 'Yes' => 'Yes' ),
		), $checkout->get_value( 'yc_permissionvideo_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_permissionleave_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'radio',
			'required' => true,
			'class' => array( 'input-radio form-row-last yc_custom_radio_checkbox' ),
			'label' => __( 'Permission to leave club premises during the day' ),
			'options' => array( 'NO' => 'No', 'Yes' => 'Yes' ),
		), $checkout->get_value( 'yc_permissionleave_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_permissionirishdb_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'radio',
			'required' => true,
			'class' => array( 'input-radio form-row-first yc_custom_radio_checkbox' ),
			'label' => __( 'Permission to add to Irish Sailing database' ),
			'options' => array( 'NO' => 'No', 'Yes' => 'Yes' )
		), $checkout->get_value( 'yc_permissionirishdb_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_clubuse_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'checkbox',
			'required' => true,
			'class' => array( 'input-text form-row-last yc_custom_radio_checkbox' ),
			'label' => __( 'I understand that Howth Yacht Club may use the contact information I have provided to send communications relating to the sailing course' ),
		), $checkout->get_value( 'yc_clubuse_' . $product_id . '_pid_qty_' . $quantity ) );

		woocommerce_form_field( 'yc_liability_' . $product_id . '_pid_qty_' . $quantity, array(
			'type' => 'checkbox',
			'required' => true,
			'class' => array( 'input-text form-row-last yc_custom_radio_checkbox' ),
			'label' => __( 'I understand that no liability is attached to Howth Yacht Club, its members or servants, for any loss or damage to property or for injury sustained by any child enrolled for this tuition.' ),
		), $checkout->get_value( 'yc_liability_' . $product_id . '_pid_qty_' . $quantity ) );
	}

	/**
	 * Add your validation rules to the Sailor form fields
	 */
	public function wmsc_validate_sailor_time_field() {

		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		if ( count( $items ) > 0 ) {
			foreach ( $items as $item => $values ) {
				$product = $values['data'];
				$pid = $product->get_id();
				if ( has_term( 'merchandise', 'product_cat', $pid ) ) {
					continue;
				}
				$counter = 1;
				while ( $counter <= $values['quantity'] ) {
					if ( isset( $_POST[ 'yc_allergy_' . $pid . '_pid_qty_' . $counter ] ) && empty( $_POST[ 'yc_allergy_' . $pid . '_pid_qty_' . $counter ] ) ) {
						wc_add_notice( __( 'Please enter Sailor Allergies/Medical Conditions.' ), 'error' );
					}
					$counter++;
				}
			}
		}
	}
	/**
	 * Check for the keys by $_POST , $_POST keys are combination based on product id and then for each qty of that product.
	 */
	public function wmsc_save_sailor_time_field( $order_id ) {
		$order = wc_get_order( $order_id );
		$quantity = 1;
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			$product_id = $product->get_id();
			$quantity = $item->get_quantity();
			$counter = 1;
			while ( $counter <= $quantity ) {
				if ( ! empty( $_POST[ 'yc_firstname_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_firstname_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_firstname_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_lastname_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_lastname_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_lastname_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_liability_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_liability_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_liability_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_clubuse_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_clubuse_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_clubuse_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_permissionirishdb_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_permissionirishdb_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_permissionirishdb_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_permissionleave_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_permissionleave_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_permissionleave_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_permissionvideo_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_permissionvideo_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_permissionvideo_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_guardianmail_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_guardianmail_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_guardianmail_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_guardiantel_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_guardiantel_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_guardiantel_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_guardianlast_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_guardianlast_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_guardianlast_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_guardianfirst_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_guardianfirst_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_guardianfirst_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_allergy_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_allergy_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_allergy_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				if ( ! empty( $_POST[ 'yc_dob_' . $product_id . '_pid_qty_' . $counter ] ) ) {
					update_post_meta( $order_id, 'yc_dob_' . $product_id . '_pid_qty_' . $counter, sanitize_text_field( $_POST[ 'yc_dob_' . $product_id . '_pid_qty_' . $counter ] ) );
				}
				$counter++;
			}
		}
	}



}