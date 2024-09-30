<?php
// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// spl_autoload_register( function($classname) {
    
// 	$class      = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
// 	$classes    = dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $class . '.php'; 
	
// 	$vendor		= str_replace( 'allergens-dietary-ictoria', DIRECTORY_SEPORATOR, '', $class);
// 	$vendor		= 'allergens-dietary-ictoria' . DIRECTORY_SEPORATOR . preg_replace('/\//', '/php/', $vendor, 1);
// 	$vendors	= dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . $vendor . '.php';

// 	if( file_exists($classes) ) {
// 		require_once( $classes );
// 	} elseif ( file_exists($vendors) ) {
// 		require_once( $vendors );
// 	}
// } );

// $index = new Allergens_dietary_ictoria.php;
// $admin = new Php\Admin\Admin_startup.php;
// $form_license = new Php\Froms\allergen_form_license.php;
// $form_allergen = new Php\Forms\allergen_form.php;
// $form_one_allergen = new Php\Forms\Iallergen_form.php;
// $language = new Php\Language\Load_language.php;
// $activator = new Php\Activator.php;
// $filter = new Php\Filter.php;
// $functions = new Php\Functions.php;
// $MyPluginAddMenu = new Php\MyPluginAddMenu.php;
// $product_settings = new Php\Product_settings.php;
// $products = new Php\Products.php;
// $wc_integration = new Php\Wc_integration.php;






//
// Plugin Name: Allergens and Dietary
// Plugin URI:
// Description: Adds Allergens and Dietary options that can be used with WooCommerce products.
// @Version:     1.0.0.0 
// Requires at least: 6.3.1
// Requires PHP: 7.4
// Author:      Ictoria.nl
// Author URI:  http://ictoria.nl
// License:     GPLv3 or later
// License URI: https://www.gnu.org/licenses/gpl-3.0.html
// Text Domain: allergens-dietary-ictoria
// Domain Path: /languages/
// WC Tested Up To: 8.1.1
//

// "Allergens and Dietary" is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// any later version.
//
// "Allergens and Dietary" is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with "Allergens and Dietary". If not, see https://www.gnu.org/licenses/gpl-3.0.html

// Set constant values that are used to retain file location references
define( 'ALLERGENS_DIETARY_ICTORIA_NAME', 'allergens-dietary-ictoria' );
define( 'ALLERGENS_DIETARY_ICTORIA_FILE', __FILE__ ); // contains the full path to the plugin file
define( 'ALLERGENS_DIETARY_ICTORIA_DIRNAME', __DIR__ );
define( 'ALLERGENS_DIETARY_ICTORIA_BASE', plugin_basename( __FILE__ ) ); // contains the path: plugin_directory/plugin_file
// Check if WooCommerce is active and store the result in a constant value
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE', true );
} else {
	define( 'ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE', false );
}

// load file with generic static methods
require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/functions.php';
add_action( 'plugins_loaded', array( 'Allergens_Dietary_Ictoria_Functions', 'load_textdomain' ) );


// class that contains the functions that are used by the activation/deactivation/uninstall hooks
class Allergens_Dietary_Ictoria_Startup {
	// function that runs when the activation hook is called
	public static function on_activation() {
		$settings = Plugin\Php\Allergens_Dietary_Ictoria_Functions::get_settings();
		// show popup asking for certain setting options if this is the first activation after installing the plugin.
		if ( ! isset( $settings[ __( 'initial_setup_done' ) ] ) ) {
			// show popup asking wether or not the user wants to automatically export all relevant product data on uninstall
			// tell user (within popup) that above setting can be set at all times from the plugin settings menu
			// save chosen settings in the allergens_dietary_ictoria_settings(WP options table)
			// add the initial_setup_done option to allergens_dietary_ictoria_settings (value: true) to prevent this popup from showing on every activation after the first
		}

		$options = Plugin\Php\Allergens_Dietary_Ictoria_Functions::get_options();
		// set the default options in the WooCommerce options table if they do not exist
		if ( empty( $options ) ) {
			$options = Plugin\Php\Allergens_Dietary_Ictoria_Functions::default_options();
			update_option( __( 'allergens_dietary_ictoria_options', 'allergens-dietary-ictoria' ), $options, true );
		}

		// temporary admin menu panel for testing the license form
		add_menu_page( 'Allergens and Dietary', 'Allergens and Dietary', 'manage_options', 'allergens-dietary-ictoria', array( 'Allergens_Dietary_Ictoria_Functions', 'admin_page' ), 'dashicons-carrot', 6 );
	}

	// function that runs when the deactivation hook is called
	public static function on_deactivation() {
		// cookies might be needed if the filter needs to store previous search settings, and will have to be removed if this function is called

		// temporary delete_option for testing without having to uninstall/reinstall. This code is also found in the uninstall.php file of this plugin
		// delete_option('allergens_dietary_ictoria_settings');
		// delete_option('allergens_dietary_ictoria_options');
	}
}

register_activation_hook( ALLERGENS_DIETARY_ICTORIA_BASE, array( 'Allergens_Dietary_Ictoria_Startup', 'on_activation' ) );
register_deactivation_hook( ALLERGENS_DIETARY_ICTORIA_BASE, array( 'Allergens_Dietary_Ictoria_Startup', 'on_deactivation' ) );




