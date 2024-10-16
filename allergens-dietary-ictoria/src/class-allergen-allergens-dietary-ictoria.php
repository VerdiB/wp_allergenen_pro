<?php


class Allergens_Dietary_Ictoria_Startup {

	private static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		error_log('construct');
		// Allergens_Dietary_Ictoria_Product_Settings::instance();
		// Allergens_Dietary_Ictoria_Activator::activate();
		// Allergens_Dietary_Ictoria_Products::instance();
		// Allergens_Dietary_Ictoria_Filter::instance();
		// Allergens_Dietary_Ictoria_Functions::load_style();
		// MyPluginAddMenu::instance();
	}

    public static function on_activation() {
		error_log('activation');

		// temporary admin menu panel for testing the license form
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

	public static function error_notice( $level, $message ) {
		$message_header = sprintf( __( '%1$sAllergens and Dietary is inactive:%2$s', 'allergens-dietary-ictoria' ), '<p><strong>', '</strong></p>' );
		$message_full   = $message_header . $message;
		add_action(
			'admin_notices',
			static function () use ( $level, $message_full ) {
				echo '<div class="notice ' . esc_attr( $level ) . '" style="padding:12px 12px">
					' . wp_kses_post( $message_full ) . '
				</div>';
			}
		);
	}
	public static function load_textdomain(){
		load_plugin_textdomain(' allergens-dietary-ictoria', false, basename(ALLERGENS_DIETARY_ICTORIA_FILE).'/l10n'); 
	}
}