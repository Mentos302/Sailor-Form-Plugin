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
			'Sailors Forms Settings',
			'Sailors Forms',
			'manage_options',
			'sailor-form-settings',
			array( $this, 'render_settings_page' )
		);
	}

	public function initialize_settings() {
		register_setting( 'sailor_form_options', 'sailors_forms_categories' ); // Change the option name to match the one used in the form
		add_settings_section( 'sailor_form_settings_section', 'Sailors Forms Categories', null, 'sailor-form-settings' );
		add_settings_field( 'sailors_forms_categories', '', array( $this, 'excluded_categories_callback' ), 'sailor-form-settings', 'sailor_form_settings_section' );

		// Use $this->yc_sailor_form_admin_styles instead of yc_sailor_form_admin_styles to correctly reference the method
		add_action( 'admin_head', array( $this, 'yc_sailor_form_admin_styles' ) );
	}

	public function render_settings_page() {
		?>
		<div class="wrap">
			<h2>Sailors Forms Settings</h2>
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
		$options = get_option( 'sailors_forms_categories' ); // Change the option name to match the one used in the form
		$categories = get_terms( 'product_cat', array( 'hide_empty' => false ) );

		$count = 0;
		$categories_per_column = 8;

		foreach ( $categories as $category ) {
			$selected_option = isset( $options[ $category->term_id ] ) ? $options[ $category->term_id ] : 'none';

			if ( $count % $categories_per_column === 0 ) {
				echo '<div style="width: 320px; display: inline-block; margin-right: 20px;">';
			}

			echo '<div style="margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; ">';
			echo '<label for="sailor_form_category_' . $category->term_id . '">' . $category->name . '</label>';
			echo '<select id="sailor_form_category_' . $category->term_id . '" name="sailors_forms_categories[' . $category->term_id . ']">'; // Change the name attribute here
			echo '<option value="none">None</option>';
			echo '<option value="adult" ' . selected( $selected_option, 'adult', false ) . '>Adult Sailor Form</option>';
			echo '<option value="instructor" ' . selected( $selected_option, 'instructor', false ) . '>Instructor Sailor Form</option>';
			echo '<option value="junior" ' . selected( $selected_option, 'junior', false ) . '>Junior Sailor Form</option>';
			echo '</select>';
			echo '</div>';

			$count++;

			if ( $count % $categories_per_column === 0 || $count === count( $categories ) ) {
				echo '</div>';
			}
		}
	}

	// Add the yc_sailor_form_admin_styles method to your class
	public function yc_sailor_form_admin_styles() {
		$current_screen = get_current_screen();
		if ( $current_screen->id === "settings_page_sailor-form-settings" ) {
			echo '<style>.form-table th[scope="row"]:empty{display:none;}.form-table .yc-sailor-form-hide-th + td{padding-left:0;}</style>';
		}
	}

	public function wpmc_add_sailor_time_step( $steps ) {
		$excluded_categories = get_option( 'sailors_forms_categories', array() );

		$show_step = false;
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();

		foreach ( $items as $item_id => $values ) {
			$product = wc_get_product( $values['product_id'] );
			$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$terms = get_the_terms( $pid, 'product_cat' );


			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( isset( $excluded_categories[ $term->term_id ] ) && $excluded_categories[ $term->term_id ] !== 'none' ) {
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



	public function yc_multiple_sailor_forms() {
		$excluded_categories = get_option( 'sailors_forms_categories', array() );

		if ( ! is_admin() && ! WC()->cart->is_empty() ) {
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			if ( count( $items ) > 0 ) {
				foreach ( $items as $item => $values ) {
					$product = $values['data'];
					$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
					$terms = get_the_terms( $pid, 'product_cat' );
					$selected_sailor_form = 'none';
					if ( is_array( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( isset( $excluded_categories[ $term->term_id ] ) ) {
								$selected_sailor_form = $excluded_categories[ $term->term_id ];
								break;
							}
						}
					}
					$counter = 1;
					while ( $counter <= $values['quantity'] ) {
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

	public function wmsc_step_content_sailor() {
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		$excluded_categories = get_option( 'sailors_forms_categories', array() );

		if ( count( $items ) > 0 ) {
			foreach ( $items as $item => $values ) {
				$product = $values['data'];
				$pid = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
				$terms = get_the_terms( $pid, 'product_cat' );

				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						if ( isset( $excluded_categories[ $term->term_id ] ) ) {
							$selected_sailor_form = $excluded_categories[ $term->term_id ];
							break;
						}
					}
				}

				if ( $selected_sailor_form === 'none' )
					continue;

				$counter = 1;
				while ( $counter <= $values['quantity'] ) {
					echo '<h5 class="form-row form-row-wide">Sailor Form Number ' . $counter . ' For ' . $product->get_name() . '</h5><div></div>';
					$this->yc_create_sailors_form_field( $pid, $counter, $selected_sailor_form );
					$counter++;
				}
			}
		}
	}

	public function yc_create_sailors_form_field( $product_id, $quantity, $selected_sailor_form ) {
		$checkout = WC()->checkout();
		$field_group_id = '';

		switch ( $selected_sailor_form ) {
			case 'adult':
				$field_group_id = 'group_65ca89c5dd7d1'; // Adult Sailor Form
				break;
			case 'instructor':
				$field_group_id = 'group_65df2a51e3a24'; // Instructor Sailor Form
				break;
			case 'junior':
				$field_group_id = 'group_65df2a5e9a236'; // Junior Sailor Form
				break;
			default:
				$field_group_id = 'group_65ca89c5dd7d1'; // Default to Adult Sailor Form if no specific Sailor Form is selected
				break;
		}

		$fields = acf_get_fields( $field_group_id );

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
		$excluded_categories = get_option( 'sailors_forms_categories', array() );
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
						// Adjust the field key based on the selected Sailor Form group
						$sailor_form_group = isset( $excluded_categories[ $term->term_id ] ) ? $excluded_categories[ $term->term_id ] : 'none';
						$field_key = 'yc_' . $sailor_form_group . '_' . $pid . '_pid_qty_' . $counter;
						if ( isset( $_POST[ $field_key ] ) && empty( $_POST[ $field_key ] ) ) {
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
		$excluded_categories = get_option( 'sailors_forms_categories', array() );

		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			$product_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$terms = get_the_terms( $product_id, 'product_cat' );
			$selected_sailor_form_group = 'group_65ca89c5dd7d1'; // Default Sailor Form group

			if ( is_array( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( isset( $excluded_categories[ $term->term_id ] ) ) {
						$selected_sailor_form = $excluded_categories[ $term->term_id ];
						switch ( $selected_sailor_form ) {
							case 'adult':
								$selected_sailor_form_group = 'group_65ca89c5dd7d1';
								break;
							case 'instructor':
								$selected_sailor_form_group = 'group_65df2a51e3a24';
								break;
							case 'junior':
								$selected_sailor_form_group = 'group_65df2a5e9a236';
								break;
							default:
								$selected_sailor_form_group = 'group_65ca89c5dd7d1'; // Default group
								break;
						}
						break;
					}
				}
			}

			$fields = acf_get_fields( $selected_sailor_form_group );
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