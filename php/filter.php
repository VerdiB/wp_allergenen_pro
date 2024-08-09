<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Allergens_Dietary_Ictoria_Filter {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Allergens_Dietary_Ictoria_Filter();
        }
    }

    private function __construct() {
        add_action('woocommerce_before_shop_loop', array($this, 'create_filter'));
        add_filter('pre_get_posts', array($this, 'filter_query'));
    }

    public function create_filter() {
        $options = Allergens_Dietary_Ictoria_Functions::get_options();
        // Create variable that is used in the loops
        $categories = array();

        $html = '<form method="post">';
        // Create the HTML for all filter options, separating them by category
        foreach ($options as $key => $value) {
            // Create array entries if they do not exist for the relevant category
            if (!in_array($value['category'], array_keys($categories))) {
                $categories[$value['category']] = '';
            }
            // Check if the option is active or not
            if ($value['status'] == 'active') {
                $checked = '';
                if (isset($_POST['allergen_filter_options'][$key])) {
                    $checked = 'checked="checked"';
                }
				// Added separate hidden input for the filter-action property so it doesn't have to call get_options again
				// also added the filter-extra part
				$categories[$value['category']] .= '<div>
					<input type="checkbox" class="checkbox ' . $value['category'] . '" name="allergen_filter_options[' . $key . ']" value="1" ' . $checked . '/>
					<input type="hidden" name="allergen_filter_action[' . $key . ']" value="' . esc_attr($value['filter-action']) . '"/>
					<span>' . $value['filter-extra'] . $value['title'] . '</span>
				</div>';
            }
        }

        // Create the HTML for the filter that this plugin adds. Does not show the category if all options of the given category are globally disabled
        foreach ($categories as $key => $value) {
            if ($categories[$key] != '') {
                $html .= '<div class="allergen-div">
                    <span>' . __(ucfirst($key), 'allergens-dietary-ictoria') . ':</span>
                </div>' . $value;
            }
        }
        $html .= '<input type="submit" name="allergen_filter" value="Filter">';
		// Added clear filter link
        $html .= '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="button clear-filters">Clear Filters</a>';
        $html .= '</form>';

        echo $html;
    }

	

    public function filter_query($query) {
        if ($query->is_main_query() && is_shop() && isset($_POST['allergen_filter'])) {
			$selected_options = isset($_POST['allergen_filter_options']) ? $_POST['allergen_filter_options'] : array();
        	$filter_actions = isset($_POST['allergen_filter_action']) ? $_POST['allergen_filter_action'] : array();

            // Check if there are any options selected
            if (!empty($selected_options)) {
                $meta_query = array();

                // Loop through each selected option and build the meta query
                foreach ($selected_options as $key => $value) {
					// A check for the filter-action property
					$action = isset($filter_actions[$key]) ? $filter_actions[$key] : 'exclude';					
					$compare = ($action === 'exclude') ? 'NOT LIKE' : 'LIKE';

                    $meta_query[] = array(
                        'key'     => 'allergens_dietary_ictoria', // Key of the custom field
                        'value'   => '"' . $key . '"', // The value to compare (key is the option name)
                        'compare' => $compare
                    );
                }

                // If there are multiple conditions, set the relationship to AND
                if (count($meta_query) > 1) {
                    $meta_query['relation'] = 'AND';
                }

                // Append the meta query to the main query
                $query->set('meta_query', $meta_query);
            }
        }
    }
}
?>
