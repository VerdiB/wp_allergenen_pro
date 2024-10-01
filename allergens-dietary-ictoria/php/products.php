<?php


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

		global $wpdb;

		$table_allergens_icons = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
		$query = $wpdb->prepare(
			"SELECT * FROM $table_allergens_icons"
		);
		
		$options = $wpdb->get_results($query, ARRAY_A);
		
		$list = get_post_meta( $post->ID, 'allergens_dietary_ictoria', true );

		if ( ! empty( $list ) ) {
			$current = array();
		
			foreach ( $options as $key => $value ) {

				// Match 'id' in $value with $list
				if ( in_array( $key['allergy_name'], $list ) ) {
					$current[ $key ] = $value['attachment_name'];  // Add the matching allergen to $current
				}else{
					echo "<pre>";
					var_dump($list);
					var_dump(" and " . $value['allergy_name']);
					echo "</pre>";
				}
			}

			echo "<pre>";
			var_dump($list);
			echo "</pre>";

			// Set the WP filter that will call the hook used to render the plugin options of this product
			$html = apply_filters( 'allergens_dietary_ictoria_render_html', $current );
		}
		if ( ! empty( $html ) ) {
			echo $html;
		}
	}

	// generate the html to display all relevant options for the given product.
	public function render_html( array $data ): string {
		$html = array();

		var_dump($data);

		foreach ( $data as $key => $value ) {

			echo "<pre>";
				var_dump($value);
				var_dump($value);
				var_dump($value);
				var_dump($value);
			echo "</pre>";
			// check if the option is globally enabled by the admin
				$icon_url = esc_url( $value['attachment_name'] );
				$title    = esc_attr( $value['allergy_name'] );
				$html[]   = "<img class='allergen-icon' src='{$icon_url}' alt='{$title}' title='{$title}' data-id='" . esc_attr( $key ) . "' />";
		}
		return implode( '', $html );
	}
}