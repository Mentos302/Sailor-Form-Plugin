<div class="wrap">
    <table class="widefat cabin-table services_fields">
        <tbody>
            <?php
			$excluded_categories = get_option( 'sailor_form_excluded_categories', array() );

			function map_acf_type_to_wc( $acf_type ) {
				$mapping = [ 
					'text' => 'text',
					'textarea' => 'textarea',
					'email' => 'email',
					'select' => 'select',
					'checkbox' => 'checkbox',
					'radio' => 'radio',
					'date_picker' => 'date',
				];
				return isset( $mapping[ $acf_type ] ) ? $mapping[ $acf_type ] : 'text';
			}

			function get_wc_field_class( $type ) {
				$classes = [ 
					'text' => 'form-row-first',
					'email' => 'form-row-first',
					'date' => 'form-row-first',
					'textarea' => 'form-row-last',
					'checkbox' => 'form-row-wide',
				];
				return isset( $classes[ $type ] ) ? $classes[ $type ] : 'form-row-wide';
			}

			foreach ( $order->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				$product_id = $product->get_id();

				$skip_product = false;
				$terms = get_the_terms( $product_id, 'product_cat' );

				if ( $terms ) {
					foreach ( $terms as $term ) {
						if ( array_key_exists( $term->term_id, $excluded_categories ) ) {
							$skip_product = true;
							break;
						}
					}
				}

				if ( $skip_product ) {
					continue;
				}

				$quantity = $item->get_quantity();
				$counter = 1;
				echo "<tr><td>";
				echo "<h1>Product Name : " . $product->get_name() . "</h1>";
				echo "</td></tr><tr><td>";



				for ( $counter = 1; $counter <= $quantity; $counter++ ) {
					$fields = acf_get_fields( 'group_65ca89c5dd7d1' );
					foreach ( $fields as $field ) {
						if ( $field['type'] === 'checkbox' ) {
							continue;
						}
						$field_key = 'yc_' . $field['name'] . '_' . $product_id . '_pid_qty_' . $counter;
						$class = get_wc_field_class( map_acf_type_to_wc( $field['type'] ) );
						$class .= ' disabled-input';
						woocommerce_form_field( $field_key, [ 
							'type' => map_acf_type_to_wc( $field['type'] ),
							'required' => $field['required'],
							'class' => [ $class ],
							'label' => __( $field['label'] ),
							'options' => isset( $field['choices'] ) ? $field['choices'] : [],
						], get_post_meta( $order->get_id(), $field_key, true ) );
					}
				}


				echo "</td></tr>";
			}
			?>
            </td>
            </tr>
        </tbody>
    </table>
    <style>
    .services_fields td {
        display: grid !important;
        grid-template-columns: 1fr 1fr;
        gap: 12px !important;
    }

    .services_fields p {
        width: 100% !important;
    }

    .services_fields .disabled-input {
        pointer-events: none;
    }

    .services_fields input[type="radio"] {
        display: none;
    }

    .services_fields input[type="radio"]:checked+label {
        font-weight: 600;

    }

    .services_fields input[type="radio"]:not(:checked)+label {
        display: none;
    }

    @media (max-width: 768px) {
        .services_fields td {
            display: grid !important;
            grid-template-columns: 1fr 1fr;
            gap: 12px !important;
        }
    }
    </style>
</div>