<?php

class Allergens_Dietary_Ictoria_Startup {
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
		add_menu_page( 'Allergens and Dietary', 'Allergens and Dietary', 'manage_options', 'allergens-dietary-ictoria', array( 'Allergens_Dietary_Allergen\Allergens_Dietary_Ictoria_Functions', 'admin_page' ), 'dashicons-carrot', 6 );

        Allergens_Dietary_Ictoria_Product_Settings::instance();
		Allergens_Dietary_Ictoria_Activator::activate();
		Allergens_Dietary_Ictoria_Products::instance();
		Allergens_Dietary_Ictoria_Filter::instance();
		Allergens_Dietary_Ictoria_Functions::load_style();
		MyPluginAddMenu::instance();
	}

	// function that runs when the deactivation hook is called
	public static function on_deactivation() {
		// cookies might be needed if the filter needs to store previous search settings, and will have to be removed if this function is called

		// temporary delete_option for testing without having to uninstall/reinstall. This code is also found in the uninstall.php file of this plugin
		// delete_option('allergens_dietary_ictoria_settings');
		// delete_option('allergens_dietary_ictoria_options');
	}
}