<?php
// exit if user can access this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Allerges_Dietary_Ictoria_Hooks {
	// displays all relevant options for the given product.
	public static function render_html( $data ) {
		$html = array();

		foreach ( $data as $key => $item ) {
			$html[] = '<img class="allergen-icon" src="' . $item['icon'] . '" alt="' . $item['title'] . '" title="' . $item['title'] . '" data-id="' . $key . '" />';
		}
		return implode( '', $html );
	}
}
add_filter( 'allergens_dietary_ictoria_render_html', array( 'Allerges_Dietary_Ictoria_Hooks', 'render_html' ) );
