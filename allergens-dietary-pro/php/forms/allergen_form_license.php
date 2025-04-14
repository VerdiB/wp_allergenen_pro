<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'I_Allergens_Dietary_Pro_Form' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/Iallergen_form.php';
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergen.php';
}

if( ! class_exists('Allergens_Dietary_Pro_License_Handler')){
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/license_handler.php';
}


/**
 * @class Allergens_Dietary_Pro_License_Form
 * @brief Class that creates the form for the license key where
 * the user can enter the license key for the plugin to get premium functions unlocked
 * @author Ictoria
 * @date 2-9-2024
 * @implements I_Allergens_Dietary_Pro_Form
 * @see I_Allergens_Dietary_Pro_Form
 * @since 1.0.0
 */
class Allergens_Dietary_Pro_License_Form implements I_Allergens_Dietary_Pro_Form {
	/**
	 * @brief Constructor for the Allergens_Dietary_Pro_License_Form class
	 * for now it is empty and does nothing but it's common courtesy to have it
	 * @return void
	 */
	public function __construct() 
	{
	}

	public function showForm( string $allergenName = null ) {


		// TODO: Getting license key that is in use by site if it exists

		$licenseHandler = new Allergens_Dietary_Pro_License_Handler();
		$license = $licenseHandler->getLicense();
		echo $license;
		var_dump($licenseHandler);
		return ;
		// $html  = '<fieldset>
		// <label for="license_key">' . __( 'License key', 'allergens-dietary-pro' ) . '</label><br>
		// <input type="text" name="license_key" id="license_key" value=""><br><br>
		// <input type="submit" class="button button-primary" id="submitButton" name="submit" value="' . __( 'Verify license key', 'allergens-dietary-pro' ) . '">
		// <p> Test';
		// $html .= '</fieldset>';
		// echo $html;
	}

	public function submit( array $data ) {
		if ( ! empty( $data ) ) {
			$post_data = $this->sanitize( $data );
			// TODO: save the license key in the external database
		} else {
			return;
		}
	}

	public function sanitize( array $data ) {
		$data['license_key'] = sanitize_text_field( wp_unslash( $data['license_key'] ) );
		return $data;
	}
}
