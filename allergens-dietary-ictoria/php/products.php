<?php

namespace Plugin\Php;

// exit if user can access this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// this class contains functions used on the front-end product pages
class Allergens_Dietary_Ictoria_Products {
	private static ?self $_instance = null;

	public static function instance(): self {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_filter( 'allergens_dietary_ictoria_render_html', array( $this, 'render_html' ) );
		add_action( 'woocommerce_single_product_summary', array( $this, 'show_product_options' ) );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'show_product_options' ) );
	}

	// get all active product options added by this plugin of the product that is currently being loaded by WooCommerce.
	public function show_product_options(): void {
		global $post;
		$html    = '';
		$options = Allergens_Dietary_Ictoria_Functions::get_options();
		$list    = get_post_meta( $post->ID, 'allergens_dietary_ictoria', true );

		if ( ! empty( $list ) ) {
			$current = array();

			foreach ( $options as $key => $value ) {
				if ( in_array( $key, $list ) ) {
					$current[ $key ] = $value;
				}
			}
			// set the WP filter that will call the hook used to render the plugin options of this product
			$html = apply_filters( 'allergens_dietary_ictoria_render_html', $current );
		}
		if ( ! empty( $html ) ) {
			echo $html;
		}
	}

	// generate the html to display all relevant options for the given product.
	public function render_html( array $data ): string {
		$html = array();

		foreach ( $data as $key => $value ) {
			// check if the option is globally enabled by the admin
			if ( $value['status'] === 'active' ) {
				$icon_url = esc_url( $value['icon'] );
				$title    = esc_attr( $value['title'] );
				$html[]   = "<img class='allergen-icon' src='{$icon_url}' alt='{$title}' title='{$title}' data-id='" . esc_attr( $key ) . "' />";
			}
		}
		return implode( '', $html );
	}
}
