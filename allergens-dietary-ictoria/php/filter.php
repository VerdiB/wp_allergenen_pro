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

	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new Allergens_Dietary_Ictoria_Filter();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		add_action('woocommerce_before_shop_loop', array($this, 'create_filter'));
		add_action('woocommerce_product_query', array($this, 'filter_query'));
		$this->_allergens = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->getAllAllergens();
	}

	public function create_filter()
	{
		// self::load_css();
		self::load_js();
		// Create variable that is used in the loops
		$html = '<form id="allergens-ictoria" method="post">';
		$html .= '<button class="button" type="button" id="dropdown-ictoria">filters</button>';

		// Create the HTML for all filter options, separating them by category
		foreach ($this->_allergens as $allergen) {
			// Check if the option is active or not
			$checked = '';
			if (isset($_POST['allergen_filter_options'][$allergen['allergy_name']])) {
				$checked = 'checked="checked"';
			}
			$html .= '<div>
				<input type="checkbox" class="checkbox" name="allergen_filter_options[' . $allergen['allergy_name'] . ']" value="' . esc_attr($allergen['allergy_name']) . '" ' . $checked . '/>
				<span>' . __(((int) $allergen['is_allergy'] === 0 ? '' : 'No ') . $allergen['allergy_name'], 'allergens-dietary-ictoria') . '</span>
			</div>';
		}

		// Create the HTML for the filter that this plugin adds. Does not show the category if all options of the given category are globally disabled

		$html .= '<input class="button clear-filters" type="submit" name="allergen_filter" value="Filter">';
		// Added clear filter link
		$html .= '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="button clear-filters">Clear Filters</a>';


		$html .= '</form>';

		echo $html;
	}

	/**
	 * @param array $query
	 * @return void
	 * @brief Calls the db and activates a query where the result will be given to woocommerce
	 * Where the input is either  allergens and/or dietary restrictions
	 * So that a customer can see selected products with certain dietary restrictions
	 * and won't see any products containing selected allergens
	 * @author Unkown
	 * @since 0.16.5.1
	 * @date 18-11-2024
	 */
	public function filter_query($query)
	{
		if ($query->is_main_query() && is_shop() && isset($_POST['allergen_filter'])) {
			$selected_options = isset($_POST['allergen_filter_options']) ? $_POST['allergen_filter_options'] : array();
			$selected_allergens = array();
			$selected_diatary = array();

			// Sort the selected options
			foreach ($this->_allergens as $allergen) {
				if (isset($selected_options[$allergen['allergy_name']]))
					//check if the allergen is an allergy or diatary restriction
					//where 0 is a dietary restriction and 1 is an allergy
					if ($allergen['is_allergy'] == 0) {
						$selected_diatary[] = $allergen['allergy_name'];
					} else {
						$selected_allergens[] = $allergen['allergy_name'];
					}
			}


			// Check if there are any options selected
			if (! empty($selected_options)) {
				$filtered_products = Allergens_Dietary_Ictoria_Allergy_Product_Queries::getInstance()->getFilteredProducts($selected_allergens, $selected_diatary);
				$product_arr = array();

				foreach ($filtered_products as $product) {
					$product_arr[] = $product['product_id'];
				}
				$query->set('post__in', $product_arr);


			}
		}
	}
	public static function load_css()
    {
        wp_register_style('allergens-dietary-ictoria-css', plugins_url(ALLERGENS_DIETARY_ICTORIA_NAME . '/assets/css/allergens-dietary-ictoria.css'));
        wp_enqueue_style('allergens-dietary-ictoria-css');
    }
	public static function load_js()
    {
        wp_register_script('Allergens_Dietary_Ictoria_Show_Allergens', plugins_url(ALLERGENS_DIETARY_ICTORIA_NAME . '/assets/js/script.js'), array('jquery'));
        wp_enqueue_script('Allergens_Dietary_Ictoria_Show_Allergens');
    }
}
