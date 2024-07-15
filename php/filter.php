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
		//add_filter('pre_get_posts', array($this, 'filter_query'));
	}
	
	public function create_filter(){
		$options = Allergens_Dietary_Ictoria_Functions::get_options();

		//create variable that is used in the loops
		$categories = array();
		
		$html = '<form method="post">';
		//create the html for all filter options, seperating them by category
		foreach($options as $key => $value){
			//create array entries if they do not exist for the relevant category
			if(!in_array($value['category'], array_keys($categories))){
				$categories[$value['category']] = '';
			}
			//check if the option is active or not
			if($value['status'] == 'active'){
				$checked = '';
				if(isset($_GET[$key])){
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
		$html .= '<input type="submit" name="allergen_filter" value="Filter"></form>';
		
		echo $html;
	}
	
	//template filter:
	//https://webkul.com/blog/how-to-woocommerce-custom-product-filter-on-shop-page/
	//attribute filtering
	//https://stackoverflow.com/questions/33790364/filter-woocommerce-products-by-custom-attribute-object/33794486#33794486
	
	//https://stackoverflow.com/questions/74200775/how-can-i-filter-woocommerce-shop-products-product-loop-by-their-custom-produc
	
	
	public function filter_query($meta_query = array()){
		if($query->is_main_query() && is_shop() && isset($_POST['allergen_filter'])){
			$options = $_POST['allergen_filter_options'];
			
			echo '<pre>';
			print_r($options);
			echo '<pre>';
			
			/*//$meta_query = array();
			foreach($options as $key => $value){
				$meta_query[] = [
					'key'     => 'allergens_dietary_ictoria', //this is the key of the serialized data (of this plugin) of the product
					'value'   => $value,
					'compare' => 'LIKE'
				];
			}*/
		}
		//return $meta_query;
	}
}
?>