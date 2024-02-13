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
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'add_plugin_settings_page' ) );
		add_action( 'admin_init', array( $this, 'initialize_settings' ) );
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yc-sailor-form-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yc-sailor-form-public.js', array( 'jquery' ), $this->version, false );
	}

	public function add_plugin_settings_page() {
		add_options_page(
			'Sailor Form Settings',
			'Sailor Form',
			'manage_options',
			'sailor-form-settings',
			array( $this, 'render_settings_page' )
		);
	}

	public function initialize_settings() {
		register_setting( 'sailor_form_options', 'sailor_form_excluded_categories' );
		add_settings_section( 'sailor_form_settings_section', 'Categories to Skip Sailor Forms', null, 'sailor-form-settings' );
		add_settings_field( 'sailor_form_excluded_categories', '', array( $this, 'excluded_categories_callback' ), 'sailor-form-settings', 'sailor_form_settings_section' );
		function yc_sailor_form_admin_styles() {
			$current_screen = get_current_screen();
			if ( $current_screen->id === "settings_page_sailor-form-settings" ) {
				echo '<style>.form-table th[scope="row"]:empty{display:none;}.form-table .yc-sailor-form-hide-th + td{padding-left:0;}</style>';
			}
		}
		add_action( 'admin_head', 'yc_sailor_form_admin_styles' );
	}

	public function render_settings_page() {
		?>
		<div class="wrap">
			<h2>Sailor Form Settings</h2>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'sailor_form_options' );
				do_settings_sections( 'sailor-form-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function excluded_categories_callback() {
		$options = get_option( 'sailor_form_excluded_categories' );
		$categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );
		foreach ( $categories as $category ) {
			$checked = isset( $options[ $category->term_id ] ) ? 'checked' : '';
			echo '<input type="checkbox" id="excluded_categories_' . $category->term_id . '" name="sailor_form_excluded_categories[' . $category->term_id . ']" value="1" ' . $checked . '>';
			echo '<label for="excluded_categories_' . $category->term_id . '"> ' . $category->name . '</label><br>';
		}
	}

	public function wpmc_add_sailor_time_step( $steps ) {
		$excluded_categories = get_option( 'sailor_form_excluded_categories', array() );
		$show_step = false;
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		foreach ( $items as $item_id => $values ) {
			$product = wc_get_product( $values['product_id'] );
			$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$terms = get_the_terms( $pid, 'product_cat' );
			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( ! array_key_exists( $term->term_id, $excluded_categories ) ) {
						$show_step = true;
						break 2;
					}
				}
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

	function yc_multiple_sailor_forms() {
		if ( ! is_admin() && ! WC()->cart->is_empty() ) {
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			if ( count( $items ) > 0 ) {
				foreach ( $items as $item => $values ) {
					$product = $values['data'];
					$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
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

	function wmsc_step_content_sailor() {
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		$excluded_categories = get_option( 'sailor_form_excluded_categories', array() );
		if ( count( $items ) > 0 ) {
			foreach ( $items as $item => $values ) {
				$product = $values['data'];
				$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
				$terms = get_the_terms( $pid, 'product_cat' );
				$skip_product = false;
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( array_key_exists( $term->term_id, $excluded_categories ) ) {
							$skip_product = true;
							break;
						}
					}
				}
				if ( ! $skip_product ) {
					$counter = 1;
					while ( $counter <= $values['quantity'] ) {
						echo '<h5 class="form-row form-row-wide">Sailor Form Number ' . $counter . ' For ' . $product->get_name() . '</h5><div></div>';
						$this->yc_create_sailors_form_field( $pid, $counter );
						$counter++;
					}
				}
			}
		}
	}

	public function yc_create_sailors_form_field( $product_id, $quantity ) {
		$checkout = WC()->checkout();
		$fields = acf_get_fields( 'group_65ca89c5dd7d1' );
		foreach ( $fields as $field ) {
			$field_key = 'yc_' . $field['name'] . '_' . $product_id . '_pid_qty_' . $quantity;
			woocommerce_form_field( $field_key, array(
				'type' => $this->map_acf_type_to_wc( $field['type'] ),
				'required' => $field['required'],
				'class' => array( $this->get_wc_field_class( $this->map_acf_type_to_wc( $field['type'] ) ) ),
				'label' => __( $field['label'] ),
				'options' => isset( $field['choices'] ) ? $field['choices'] : array(),
			), $checkout->get_value( $field_key ) );
		}
	}

	private function map_acf_type_to_wc( $acf_type ) {
		$mapping = array( 'text' => 'text', 'textarea' => 'textarea', 'email' => 'email', 'select' => 'select', 'checkbox' => 'checkbox', 'radio' => 'radio', 'date_picker' => 'date' );
		return isset( $mapping[ $acf_type ] ) ? $mapping[ $acf_type ] : 'text';
	}

	private function get_wc_field_class( $type ) {
		$classes = array( 'text' => 'form-row-first', 'email' => 'form-row-first', 'date' => 'form-row-first', 'textarea' => 'form-row-last', 'checkbox' => 'form-row-wide' );
		return isset( $classes[ $type ] ) ? $classes[ $type ] : 'form-row-wide';
	}

	public function wmsc_validate_sailor_time_field() {
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		$excluded_categories = get_option( 'sailor_form_excluded_categories', array() );
		if ( count( $items ) > 0 ) {
			foreach ( $items as $item => $values ) {
				$product = $values['data'];
				$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
				$skip_validation = false;
				$terms = get_the_terms( $pid, 'product_cat' );
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( isset( $excluded_categories[ $term->term_id ] ) ) {
							$skip_validation = true;
							break;
						}
					}
				}
				if ( ! $skip_validation ) {
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
	}

	public function wmsc_save_sailor_time_field( $order_id ) {
		$order = wc_get_order( $order_id );
		$fields = acf_get_fields( 'group_65ca89c5dd7d1' );
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			$product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$quantity = $item->get_quantity();
			for ( $counter = 1; $counter <= $quantity; $counter++ ) {
				foreach ( $fields as $field ) {
					$field_key = 'yc_' . $field['name'] . '_' . $product_id . '_pid_qty_' . $counter;
					if ( isset( $_POST[ $field_key ] ) ) {
						$sanitized_value = sanitize_text_field( $_POST[ $field_key ] );
						update_post_meta( $order_id, $field_key, $sanitized_value );
					}
				}
			}
		}
	}
}