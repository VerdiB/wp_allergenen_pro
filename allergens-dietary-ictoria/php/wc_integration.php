<?php
// exit if user can access this file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
}

/**
 * @brief This class contains functions that are used for the global plugin settings within WooCommerce
 * @since 1.0.0
 * @important
 * This class might be removed in the future if it is deemed not needed.
 * This only if the plugin is incorporating it's own dashboard. 
 */

// this class extension contains functions that are used for the global plugin settings within WooCommerce
class Allergens_Dietary_Ictoria_Wc_Integration_Settings extends WC_Integration {

	public function __construct() {
		$this->id                 = 'allergens_dietary_ictoria';

		$this->method_title       = __( 'Allergens and Dietary Ictoria plugin settings', 'allergens-dietary-ictoria' );
		$this->method_description = '';

		// check if the save button on the settings page of this plugin was pressed and run the save_settings function if it was pressed
		$this->init_form_fields();
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
		// set settings link on the plugin page
		add_filter( 'plugin_action_links_' . ALLERGENS_DIETARY_ICTORIA_BASE, array( $this, 'plugin_action_links' ) );
	}

	public function init_form_fields() {
		// using a custom type causes WooCommerce to try and call $this->generate_{value of type}_html();
		$this->form_fields = array(
			'select_items' => array(
				'type'     => __( 'allergensdietary' ),
				'desc_tip' => false,
			),
		);
		// replace above code with the code below once the generate_pluginsettings_html function exists
		// also rename the current generate_allergensdietary_html function to generate_pluginoptions_html
		/*
		$this->form_fields = array(
			'plugin_settings' => array(
				'type'          => 'pluginsettings',
				'desc_tip'      => false
			),
			'plugin_options' => array(
				'type'          => 'pluginoptions',
				'desc_tip'      => false
			)
		);*/
	}

	// generate a block of html with all allergens and dietary options that the admin can change and put it into a variable for each category
	public function generate_allergensdietary_html() {

		// create variables that are used in the loops
		$categories = array();
		$active     = array();

		$html = '<div id="allergens_dietary_ictoria_product_data allergens_dietary_ictoria_settings" class="panel woocommerce_options_panel">
		<p class="settings_header">' . __( 'Enable and disable allergens and dietary options on the whole website. This only changes the availability of the options and does not add or remove them from products', 'allergens-dietary-ictoria' ) . ':</p>';

		// create the full allergen & dietary options html. Also sets the category checkboxes to checked if at least 1 option in the given category is currently active
		foreach ( $categories as $key => $value ) {
			$checked = '';
			if ( $active[ $key ] > 0 ) {
				$checked = 'checked="checked"';
			}
			$html .= '<div class="allergen-div">
				<p>
					<input type="checkbox" class="allergens-dietary-category" name="' . $key . '" value="1" ' . $checked . '/>
					' . __( ucfirst( $key ), 'allergens-dietary-ictoria' ) . ':
				</p>
				' . $value . '
			</div>';
		}

		$html .= '</div>';
		return $html;
	}

	// create and return a link that shows up on the plugin page and redirects the user to the settings of this plugin in the integrations tab of WooCommerce when clicked
	public static function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=integration&section=allergens_dietary_ictoria' ), __( 'Settings', 'allergens-dietary-ictoria' ) );
		array_unshift( $links, $settings_link );

		return $links;
	}
}
