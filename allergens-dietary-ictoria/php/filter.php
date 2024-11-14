<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

if (! class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
}



class Allergens_Dietary_Ictoria_Filter
{
	private static $_instance = null;
	private array $_allergens;
	private static string $_shop_shortcode = '[products]';

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new Allergens_Dietary_Ictoria_Filter();
		}
	}

	private function __construct()
	{
		add_action('woocommerce_before_shop_loop', array($this, 'create_filter'));
		add_filter('pre_get_posts', array($this, 'filter_query'));
		// add_filter( 'woocommerce_shortcode_products_query', array( $this, 'filter_query' ) );
		// add_filter( 'woocommerce_shortcode_products_query', array( $this, 'getAllergenFilterShortcode' ) );
		$this->_allergens = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->getAllAllergens();
	}

	public function create_filter()
	{
		// Create variable that is used in the loops
		// print_r($_POST);

		error_log('create_filter');

		$html = '<form id="allergens-ictoria" method="post">';

		echo '<button type="button" id="dropdown-ictoria">filters</button>';

		// Create the HTML for all filter options, separating them by category
		foreach ($this->_allergens as $allergen) {
			// Check if the option is active or not
			$checked = '';
			if (isset($_POST['allergen_filter_options'][$allergen['allergy_name']])) {
				$checked = 'checked="checked"';
			}
			// Added separate hidden input for the filter-action property so it doesn't have to call get_options again
			// also added the filter-extra part
			$html .= '<div>
				<input type="checkbox" class="checkbox" name="allergen_filter_options[' . $allergen['allergy_name'] . ']" value="' . esc_attr($allergen['allergy_name']) . '" ' . $checked . '/>
				<input type="hidden" name="allergen_filter_action[' . $allergen['allergy_name'] . ']" value="' . ((int)$allergen['is_allergy'] === 0 ? 'include' : 'exclude') . '"/>
				<span>' . __(((int) $allergen['is_allergy'] === 0 ? '' : 'No ') . $allergen['allergy_name'], 'allergens-dietary-ictoria') . '</span>
			</div>';
		}

		// Create the HTML for the filter that this plugin adds. Does not show the category if all options of the given category are globally disabled

		$html .= '<input type="submit" name="allergen_filter" value="Filter">';
		// Added clear filter link
		$html .= '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="button clear-filters">Clear Filters</a>';


		$html .= '</form>';

		echo $html;
	}


	public function getAllergenFilterShortcode()
	{
		return $this->_shop_shortcode;
	}

	public $count = 0;

	public function filter_query($query)
	{
		if ($query->is_main_query() && is_shop()) {
			// Count should only increase once per page load now
			$this->count++;
			error_log("Running filter_query count: " . $this->count);

			if (isset($_POST['allergen_filter'])) {
				$selected_options = isset($_POST['allergen_filter_options']) ? $_POST['allergen_filter_options'] : array();
				$selected_allergens = array();
				$selected_diatary = array();

				// Sort the selected options
				foreach ($this->_allergens as $allergen) {
					if (isset($selected_options[$allergen['allergy_name']])) {
						if ($allergen['is_allergy'] == 0) {
							$selected_diatary[] = $allergen['allergy_name'];
						} else {
							$selected_allergens[] = $allergen['allergy_name'];
						}
					}
				}

				if (!empty($selected_options)) {
					$filtered_products = Allergens_Dietary_Ictoria_Allergy_Product_Queries::getInstance()->getFilteredProducts($selected_allergens, $selected_diatary);
					error_log("Filtered Products: " . print_r($filtered_products, true));
					$product_ids = array();

					foreach ($filtered_products as $product) {
						$product_ids[] = $product['product_id'];
					}

					$args = array(
						'include' => $product_ids,
					);
					// $query = new WC_Product_Query();
					$products = wc_get_products($args);
					// error_log("Products: " . print_r($products, true));
					// return $products;
				}
			}
		}
	}
}
