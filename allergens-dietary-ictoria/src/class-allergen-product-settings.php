<?php

namespace Allergen;

// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// this class contains functions used to add/remove allergens and dietary options to/from a WooCommerce product
class Allergens_Dietary_Ictoria_Product_Settings {
	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Allergens_Dietary_Ictoria_Product_Settings();
		}
	}

	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'data_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'data_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_options' ) );
	}

	// function that sets the name of the menu tab for this plugin
	public function data_tab( $product_data_tabs ) {
		$product_data_tabs['allergens-tab'] = array(
			'label'  => __( 'Allergens', 'allergens-dietary-ictoria' ),
			'target' => __( 'allergens_dietary_ictoria_product_data' ),
		);
		return $product_data_tabs;
	}

	// function that shows all available options when the menu tab of this plugin is selected
	public function data_fields() {
		global $post;

		$options = Allergens_Dietary_Ictoria_Functions::get_options();
		$list    = get_post_meta( $post->ID, __( 'allergens_dietary_ictoria' ), true );
		if ( empty( $list ) ) {
			$list = array();
		}

		// create variables that are used in the loops of this function
		$categories = array();
		$active     = array();

		$html = '<div id="allergens_dietary_ictoria_product_data" class="panel woocommerce_options_panel">';
		// create the html for all options, seperating them by category
		foreach ( $options as $key => $value ) {
			// create array entries if they do not exist for the relevant category
			if ( ! in_array( $value['category'], array_keys( $categories ) ) ) {
				$categories[ $value['category'] ] = '';
				$active[ $value['category'] ]     = 0;
			}
			// check if option is globally enabled
			if ( $value['status'] == 'active' ) {
				++$active[ $value['category'] ];

				$checked = '';
				// check if the option on this product is active
				// always return false. Options do not seem to get saved in the meta
				if ( in_array( $key, $list ) ) {
					$checked = 'checked="checked"';
				}
				// add the html to the relevant array entry
				$categories[ $value['category'] ] .= '<div class="allergen-field">
					<input type="checkbox" class="checkbox ' . $value['category'] . '" name="' . $key . '_allergens_dietary_ictoria_option" id="' . $key . '_allergens_dietary_ictoria_option" value="1" ' . $checked . '/>
					<span class="description">
						<img src="' . $value['icon'] . '"/>&nbsp;' . $value['title'] . '
					</span>
				</div>';
			}
		}

		// create the full options html. Does not show the category if all options of the given category are globally disabled
		foreach ( $categories as $key => $value ) {
			$checked = '';
			if ( $active[ $key ] > 0 ) {
				$html .= '<div class="allergen-div"><p>' . __( ucfirst( $key ), 'allergens-dietary-ictoria' ) . ':</p>' . $value . '</div>';
			}
		}
		$html .= '</div>';

		echo $html;
	}

	// function that stores all selected options in the productdata of the currently selected product
	public function save_product_options( $post_id ) {
		$options = Allergens_Dietary_Ictoria_Functions::get_options();

		$list = array();
		foreach ( $options as $key => $value ) {
			if ( isset( $_POST[ $key . __( '_allergens_dietary_ictoria_option' ) ] ) ) {
				$list[] = $key;
			}
		}
		update_post_meta( $post_id, __( 'allergens_dietary_ictoria' ), $list );
	}
}
