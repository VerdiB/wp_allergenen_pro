<?php
//exit if user can access this file directly
if (!defined('ABSPATH')) {
    exit;
}

/*
Plugin Name: Allergens and Dietary
Plugin URI:
Description: Adds Allergens and Dietary options that can be used with WooCommerce products
Version:     1.00
Requires at least: 6.3.1
Requires PHP: 7.4
Author:      Ictoria.nl
Author URI:  http://ictoria.nl
License:     GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: allergens-dietary-ictoria
Domain Path: /languages/
WC tested up to: 8.1.1

"Allergens and Dietary" is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

"Allergens and Dietary" is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with "Allergens and Dietary". If not, see https://www.gnu.org/licenses/licenses/gpl-3.0.html
 */

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

/*
Plugin Name: Allergens and Dietary
Text Domain: allergens-dietary-icotoria
Domain Path: /languages/
*/
class load_language {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'translation_init' ) );
	}

	function translation_init() {
		load_plugin_textdomain( 'allergens-dietary-ictoria', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

$nl_NL = new load_language();


// class that contains the functions that are used by the activation/deactivation/uninstall hooks
class Allergens_Dietary_Ictoria_Startup {
	// function that runs when the activation hook is called
	public static function on_activation() {
		$settings = Allergens_Dietary_Ictoria_Functions::get_settings();
		// show popup asking for certain setting options if this is the first activation after installing the plugin.
		if ( ! isset( $settings[ __( 'initial_setup_done' ) ] ) ) {
			// show popup asking wether or not the user wants to automatically export all relevant product data on uninstall
			// tell user (within popup) that above setting can be set at all times from the plugin settings menu
			// save chosen settings in the allergens_dietary_ictoria_settings(WP options table)
			// add the initial_setup_done option to allergens_dietary_ictoria_settings (value: true) to prevent this popup from showing on every activation after the first
		}

		$options = Allergens_Dietary_Ictoria_Functions::get_options();
		// set the default options in the WooCommerce options table if they do not exist
		if ( empty( $options ) ) {
			$options = Allergens_Dietary_Ictoria_Functions::default_options();
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

if ( ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE ) {
	if ( is_admin() ) {
		// class is used to add the plugin to the list of integrated plugins that WooCommerce uses
		class Allergens_Dietary_Ictoria_Wc_Integration_Startup {
			public function __construct() {
				add_action( 'plugins_loaded', array( $this, 'init_integration' ) );
			}

			public function init_integration() {
				// Check if the WC_Integration class exists
				if ( class_exists( 'WC_Integration' ) ) {
					include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/wc_integration.php';
					add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
					// load the plugin admin js files
					Allergens_Dietary_Ictoria_Functions::load_admin_js();
				} else {
					// the integration class of WooCommerce was not found, show error message
					$level   = 'notice-error';
					$message = sprintf( __( '%1$sThe WooCommerce Integration class was not found. Please make sure WooCommerce is installed correctly%2$s', 'allergens-dietary-ictoria' ), '<p>', '</p>' );
					Allergens_Dietary_Ictoria_Functions::error_notice( $level, $message );
				}
			}

			public function add_integration( $integrations ) {
				$integrations[] = 'Allergens_Dietary_Ictoria_Wc_Integration_Settings';
				return $integrations;
			}
		}
		$Allergens_Dietary_Ictoria_Wc_Integration_Startup = new Allergens_Dietary_Ictoria_Wc_Integration_Startup( __FILE__ );
		// load and run the plugin admin files
		include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/product_settings.php';
		Allergens_Dietary_Ictoria_Product_Settings::instance();

		include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/activator.php';
		Allergens_Dietary_Ictoria_Activator::activate();

	}
	// load generic files used by the plugin when active
	include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/products.php';
	include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/filter.php';

	// Load dashboard
	include_once(ALLERGENS_DIETARY_ICTORIA_DIRNAME.'/php/dashboard/ictoria-admin-menu.php');
	
	Allergens_Dietary_Ictoria_Products::instance();
	Allergens_Dietary_Ictoria_Filter::instance();
	Allergens_Dietary_Ictoria_Functions::load_style();
	include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/MyPluginAddMenu.php';
	MyPluginAddMenu::instance();

if (ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE) {
    if (is_admin()) {
        //class is used to add the plugin to the list of integrated plugins that WooCommerce uses
        class Allergens_Dietary_Ictoria_Wc_Integration_Startup
        {
            public function __construct()
            {
                add_action('plugins_loaded', array($this, 'init_integration'));
            }

            public function init_integration()
            {
                //Check if the WC_Integration class exists
                if (class_exists('WC_Integration')) {
                    include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/wc_integration.php';
                    add_filter('woocommerce_integrations', array($this, 'add_integration'));
                    //load the plugin admin js files
                    Allergens_Dietary_Ictoria_Functions::load_admin_js();
                } else {
                    //the integration class of WooCommerce was not found, show error message
                    $level = 'notice-error';
                    $message = sprintf(__('%1$sThe WooCommerce Integration class was not found. Please make sure WooCommerce is installed correctly%2$s', 'allergens-dietary-ictoria'), '<p>', '</p>');
                    Allergens_Dietary_Ictoria_Functions::error_notice($level, $message);
                }
            }

            public function add_integration($integrations)
            {
                $integrations[] = 'Allergens_Dietary_Ictoria_Wc_Integration_Settings';
                return $integrations;
            }
        }
        $Allergens_Dietary_Ictoria_Wc_Integration_Startup = new Allergens_Dietary_Ictoria_Wc_Integration_Startup(__FILE__);
        //load and run the plugin admin files
        include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/product_settings.php';
        Allergens_Dietary_Ictoria_Product_Settings::instance();

        include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/activator.php';
        Allergens_Dietary_Ictoria_Activator::activate();

    }
    //load generic files used by the plugin when active
    include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/products.php';
    include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/filter.php';

    // Load dashboard
    // include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/dashboard/index.php';
    include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/dashboard/ictoria-admin-menu.php';

    Allergens_Dietary_Ictoria_Products::instance();
    Allergens_Dietary_Ictoria_Filter::instance();
    Allergens_Dietary_Ictoria_Functions::load_style();
} else {
	// WooCommerce is not installed or inactive, show error message
	$level   = 'notice-error';
	$message = sprintf( __( '%1$sWooCommerce is inactive or not installed. Please install & activate WooCommerce%2$s', 'allergens-dietary-ictoria' ), '<p>', '</p>' );
	Allergens_Dietary_Ictoria_Functions::error_notice( $level, $message );
}
