<?php
//exit if user can access this file directly.
if(!defined('ABSPATH')){
	exit;
}

//register_activation_hook(ALLERGENS_WP_ICTORIA_BASE, array('Allergens_Wp_Ictoria_Setup', 'on_activation'));
//register_uninstall_hook(ALLERGENS_WP_ICTORIA_BASE, array('Allergens_Wp_Ictoria_Setup', 'on_deactivation'));
//register_deactivation_hook(ALLERGENS_WP_ICTORIA_BASE, array('Allergens_Wp_Ictoria_Setup', 'on_uninstall'));

class Allergens_Wp_Ictoria_Setup{
	
	//function that runs when the plugin is activated and sets the default options in the WooCommerce options table if they do not exist.
	/*public static function on_activation(){
		$functions = 'Allergens_Wp_Ictoria_Functions';
		$options = $functions::get_options();
		
		if(empty($options)){
			$functions::load_textdomain();
			$options = array(
				'peanuts' => array(
					'category' => 'allergen',
					'title' => __('Peanuts', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/peanuts.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'nuts' => array(
					'category' => 'allergen',
					'title' => __('Nuts', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/nuts.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'crustaceans' => array(
					'category' => 'allergen',
					'title' => __('Crustaceans', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/crustaceans.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'molluscs' => array(
					'category' => 'allergen',
					'title' => __('Molluscs', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/molluscs.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'fish' => array(
					'category' => 'allergen',
					'title' => __('Fish', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/fish.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'eggs' => array(
					'category' => 'allergen',
					'title' => __('Eggs', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/eggs.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'milk' => array(
					'category' => 'allergen',
					'title' => __('Milk', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/milk.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'gluten' => array(
					'category' => 'allergen',
					'title' => __('Gluten', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/gluten.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'soya' => array(
					'category' => 'allergen',
					'title' => __('Soya', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/soya.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'sesame' => array(
					'category' => 'allergen',
					'title' => __('Sesame', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/sesame.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'celery' => array(
					'category' => 'allergen',
					'title' => __('Celery', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/celery.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'mustard' => array(
					'category' => 'allergen',
					'title' => __('Mustard', 'allergens-wp-ictoria'),
					'icon'  => plugins_url('allergens-wp-ictoria/assets/icons/mustard.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'lupin' => array(
					'category' => 'allergen',
					'title' => __('Lupin', 'allergens-wp-ictoria'),
					'icon' => plugins_url('allergens-wp-ictoria/assets/icons/lupin.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'sulphur' => array(
					'category' => 'allergen',
					'title' => __('Sulphur', 'allergens-wp-ictoria'),
					'icon' => plugins_url('allergens-wp-ictoria/assets/icons/sulphur.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				'vegetarian' => array(
					'category' => 'preference',
					'title' => __('Vegetarian', 'allergens-wp-ictoria'),
					'icon' => plugins_url('allergens-wp-ictoria/assets/icons/vegan.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				// !! placeholder icon !!
				'vegan' => array(
					'category' => 'preference',
					'title' => __('Vegan', 'allergens-wp-ictoria'),
					'icon' => plugins_url('allergens-wp-ictoria/assets/icons/vegan.png', ALLERGENS_WP_ICTORIA_FILE)
				),
				// !! placeholder icon !!
				'halal' => array(
					'category' => 'preference',
					'title' => __('Halal', 'allergens-wp-ictoria'),
					'icon' => plugins_url('allergens-wp-ictoria/assets/icons/vegan.png', ALLERGENS_WP_ICTORIA_FILE)
				)
			);
		}
		update_option('allergens_wp_ictoria_options', $options, true);
	}
	//function that is called on deactivation
	function static on_deactivation(){
		
		
		//lines below this are used for direct testing and manually clearing some data
		//delete_option('allergens_wp_ictoria_options');
		//Allergens_Wp_Ictoria_Functions::show_options
	}
	
	//function that is called when uninstalling this plugin.
	function static on_uninstall(){
		//add popup with confirmation of data removal (options table, custom images?)
		//delete related constants?
		delete_option('allergens_wp_ictoria_options');
	}*/
}

?>