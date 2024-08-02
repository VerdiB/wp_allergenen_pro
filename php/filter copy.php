<?php
//exit if user can access this file directly
if(!defined('ABSPATH')){
	exit;
}


class Allergens_Dietary_Ictoria_Filter{
	private static $_instance = null;

	public static function instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new Allergens_Dietary_Ictoria_Filter();
		}
	}
	
	public function __construct(){
		add_action('woocommerce_before_shop_loop', array($this, 'create_filter'));
		add_filter('pre_get_posts', array($this, 'filter_query'));
	}
	
	public function create_filter(){
		$options = Allergens_Dietary_Ictoria_Functions::get_options();
        $shop_url = get_permalink(wc_get_page_id('shop')) . '?post_type=product';

		//create variable that is used in the loops
		$categories = array();
		
		$html = '<form method="post" action="' . esc_url($shop_url) . '">';
		//create the html for all filter options, seperating them by category
		foreach($options as $key => $value){
			//create array entries if they do not exist for the relevant category
			if(!in_array($value['category'], array_keys($categories))){
				$categories[$value['category']] = '';
			}
			
			//check if the option is active or not
			if($value['status'] == 'active'){
				$checked = '';
				if(isset($_POST['allergen_filter_options'][$key])){
					$checked = 'checked="checked"';
				}
				$categories[$value['category']] .= '<div>
					<input type="checkbox" class="checkbox '.$value['category'].'" name="allergen_filter_options['.$key.']" value="1" '.$checked.'/>
					<span>'.$value['title'].'</span>
				</div>';
			}//.$value['extra'].strtolower($value['title'])
		}
		
		//create the html for the filter that this plugin adds. Does not show the category if all options of the given category are globally disabled
		foreach($categories as $key => $value){
			if($categories[$key] != '')
			{
				$html.= '<div class="allergen-div">
					<span>'.__(ucfirst($key), 'allergens-dietary-ictoria').':</span>
				</div>'
				.$value;
			}
		}
        $html .= '<input type="submit" name="allergen_filter" value="Filter">';
        $html .= '<a href="' . esc_url($shop_url) . '" class="button clear-filters">Clear Filters</a>';
        $html .= '</form>';
		
		echo $html;
	}
	
	//template filter:
	//https://webkul.com/blog/how-to-woocommerce-custom-product-filter-on-shop-page/
	//attribute filtering
	//https://stackoverflow.com/questions/33790364/filter-woocommerce-products-by-custom-attribute-object/33794486#33794486
	
	//https://stackoverflow.com/questions/74200775/how-can-i-filter-woocommerce-shop-products-product-loop-by-their-custom-produc

	public function filter_query($query){
		if($query->is_main_query() && is_shop() && isset($_POST['allergen_filter'])){
			$options = isset($_POST['allergen_filter_options']) ? $_POST['allergen_filter_options'] : array();
	
			// Check if there are any options selected
			if(!empty($options)){
				$meta_query = array();
	
				// Loop through each selected option and build the meta query
				foreach($options as $key => $value){
					$meta_query[] = array(
						'key'     => 'allergens_dietary_ictoria', // Key of the custom field
						'value'   => $key, // The value to compare (key is the option name)
						'compare' => '='
					);
				}
	
				// If there are multiple conditions, set the relationship to AND
				if(count($meta_query) > 1){
					$meta_query['relation'] = 'AND';
				}
	
				// Append the meta query to the main query
				$query->set('meta_query', $meta_query);
			}
		}
	}
}
?>