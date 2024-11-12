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
	private array $_allergens;
	private static string $_shop_shortcode = '[products]';

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Allergens_Dietary_Ictoria_Filter();
		}
	}

	private function __construct() {
		add_action( 'woocommerce_before_shop_loop', array( $this, 'create_filter' ) );
		// add_filter( 'pre_get_posts', array( $this, 'filter_query' ) );
		add_filter( 'woocommerce_shortcode_products_query', array( $this, 'getAllergenFilterShortcode' ) );
		$this->_allergens = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->getAllAllergens();
	}

	public function create_filter() {
		// Create variable that is used in the loops

		$html = '<form id="allergens-ictoria" method="post">';

		echo '<button type="button" id="dropdown-ictoria">filters</button>';

		// Create the HTML for all filter options, separating them by category
		foreach ( $this->_allergens as $allergen ) {
			// Check if the option is active or not
			$checked = '';
			if ( isset( $_POST['allergen_filter_options'][ $allergen['allergy_name'] ] ) ) {
				$checked = 'checked="checked"';
			}
			// Added separate hidden input for the filter-action property so it doesn't have to call get_options again
			// also added the filter-extra part
			$html .= '<div>
				<input type="checkbox" class="checkbox" name="allergen_filter_options[' . $allergen['allergy_name'] . ']" value="'.esc_attr($allergen['allergy_name']) .'" ' . $checked . '/>
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


	public function getAllergenFilterShortcode() {
		return $this->_shop_shortcode;
	}



	public function filter_query( $query ) {		
		if ( $query->is_main_query() && is_shop() && isset( $_POST['allergen_filter'] ) ) {			
			$selected_options = isset( $_POST['allergen_filter_options'] ) ? $_POST['allergen_filter_options'] : array();
			// $filter_actions   = isset( $_POST['allergen_filter_action'] ) ? $_POST['allergen_filter_action'] : array();
			$selected_allergens = array();
			$selected_diatary = array();

			// Sort the selected options
			foreach($this->_allergens as $allergen){
				if(isset($selected_options[$allergen['allergy_name']]))
					//check if the allergen is an allergy or diatary restriction
					//where 0 is a dietary restriction and 1 is an allergy
					if($allergen['is_allergy'] == 0)
					{
						$selected_diatary[] = $allergen['allergy_name'];
					}else{
						$selected_allergens[] = $allergen['allergy_name'];
					}
			}

			
			// Check if there are any options selected
			if ( ! empty( $selected_options ) ) {
				$filtered_products = Allergens_Dietary_Ictoria_Allergy_Product_Queries::getInstance()->getFilteredProducts( $selected_allergens, $selected_diatary );
				$tmpArr = array();
				$woo_arg = array(['inlcude']);
				// $meta_query = array();
				
				// if ( ! empty( $meta_query ) ) {
					// 	$meta_query['relation'] = 'AND';
					// 	$query->set( 'meta_query', $meta_query );
					// }
					
					foreach ( $filtered_products as $product ) {
						$tmpArr[]= $product['product_id'];
					}
					$woo_arg['include'] = $tmpArr;
				// self::$_shop_shortcode = $shortcode;
				return wc_get_products($woo_arg);

				// echo '<pre>';	
				// echo 'selected_options: <br/>';
				// print_r($selected_options);

				// echo 'selected_allergens: <br/>';
				// print_r($selected_allergens);

				// echo 'selected_diatary: <br/>';
				// print_r($selected_diatary);

				// echo 'filtered: <br/>';
				// print_r($filtered_products);

				// echo 'wpdb-> query:<br/>';
				// print_r($query);

				// echo'filter_actions: <br/>';
				// print_r($filter_actions);

				// echo 'selected_options_sorted: <br/>';
				// print_r($selected_options_sorted);

				// echo'query: <br/>';
				// print_r($query);

				// echo 'meta_query: <br/>';
				// print_r($meta_query);
				// echo '</pre>';
			}
		}
	}
}
