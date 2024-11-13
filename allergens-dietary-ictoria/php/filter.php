<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
}

class Allergens_Dietary_Ictoria_Filter {
	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Allergens_Dietary_Ictoria_Filter();
		}
	}

	private function __construct() {
		add_action( 'woocommerce_before_shop_loop', array( $this, 'create_filter' ) );
		add_filter( 'pre_get_posts', array( $this, 'filter_query' ) );
	}

	public function create_filter() {
		$allergens = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->getAllAllergens();
		// Create variable that is used in the loops
		
		$html = '<form id="allergens-ictoria" method="post">';

		echo '<button type="button" id="dropdown-ictoria">filters</button>';

		// Create the HTML for all filter options, separating them by category
		foreach ( $allergens as $allergen ) {
			// Check if the option is active or not
			$checked = '';
			if ( isset( $_POST['allergen_filter_options'][ $allergen['allergy_name'] ] ) ) {
				$checked = 'checked="checked"';
			}
			// Added separate hidden input for the filter-action property so it doesn't have to call get_options again
			// also added the filter-extra part
			$html .= '<div>
				<input type="checkbox" class="checkbox" name="allergen_filter_options[' . $allergen['allergy_name'] . ']" value="'.esc_attr($allergen['allergy_name']).'" ' . $checked . '/>
				<input type="hidden" name="allergen_filter_action[' . $allergen['allergy_name'] . ']" value="'.((int)$allergen['is_allergy'] === 0 ?'include':'exclude').'"/>
				<span>' . __(((int) $allergen['is_allergy'] === 0 ? '' : 'No ' ) . $allergen['allergy_name'], 'allergens-dietary-ictoria') . '</span>
			</div>';
		}

		// Create the HTML for the filter that this plugin adds. Does not show the category if all options of the given category are globally disabled
		
		$html .= '<input type="submit" name="allergen_filter" value="Filter">';
		// Added clear filter link
		$html .= '<a href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '" class="button clear-filters">Clear Filters</a>';
		$html .= '</form>';

		echo $html;
	}



	public function filter_query( $query ) {		
		if ( $query->is_main_query() && is_shop() && isset( $_POST['allergen_filter'] ) ) {			
			$selected_options = isset( $_POST['allergen_filter_options'] ) ? $_POST['allergen_filter_options'] : array();
			$filter_actions   = isset( $_POST['allergen_filter_action'] ) ? $_POST['allergen_filter_action'] : array();

			// Check if there are any options selected
			if ( ! empty( $selected_options ) ) {
				$meta_query = array();

				// Loop through each selected option and build the meta query
				foreach ( $selected_options as $key => $value ) {
					// A check for the filter-action property
					if (!isset($filter_actions[$key])) {
						continue;
					}
	
					$compare = $filter_actions[$key] === 'exclude' ? 'NOT LIKE' : 'LIKE';
	
					$meta_query[] = array(
						'key'     => 'allergens_dietary_ictoria', // Key of the custom field
						'value'   => $key, // The value to compare (key is the option name)
						'compare' => $compare,
						
					);
				}

				// If there are multiple conditions, set the relationship to AND
				if ( ! empty( $meta_query ) ) {
					$meta_query['relation'] = 'AND';
					$query->set( 'meta_query', $meta_query );
				}
			}
		}
	}
}
