<?php

// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'I_Allergens_Dietary_Ictoria_Form' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/Iallergen_form.php';
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergy_Attachment_Queries' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Attachment_Queries' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergy_Attachment_Queries' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

	/**
	 * @brief This shows the tabs on add/update allergens .
	 * @author T.K.
	 * @since 1.0.0
	 * @date 18-9-2024
	 */

	if ( ! class_exists( 'Allergens_Dietary_Ictoria_Tabs' ) ) {
		require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
	}

	/********************************************************************/

class Allergens_Dietary_Ictoria_Allergen_Form implements I_Allergens_Dietary_Ictoria_Form {

	private ?array $_allergen = null;
	private static ?self $_instance = null;
	private const MIME_TYPES = array( 'image/png', 'image/jpeg', 'image/jpg' );

	public function __construct() {
	}

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * @param string|null $allergenName
	 * @brief This method shows the form to add/update allergens .
	 * @return void
	 * @author V.B.
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function showForm( ?string $allergenName = null ) {

		Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();

		if ( ! is_null( $allergenName ) ) {
			// TODO: Implement showForm() method. when the allergen name is not null
			if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergy_Attachment_Queries' ) ) {
				require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
			}
			$this->_allergen = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->getAllergyAttachment( $allergenName );
			return;
		}

		$html  = '<fieldset>';
		$html .= '<label for="allergen_name">' . __( 'Allergen name', 'allergens-dietary-ictoria' ) . '</label><br>';
		$html .= '<input type="text" name="allergen_name" id="allergen_name" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_name'] : '' ) . '"/><br><br>';
		$html .= '<label for="allergen_description">' . __( 'Allergen description', 'allergens-dietary-ictoria' ) . '</label><br>';
		$html .= '<input type="text" name="allergen_description" id="allergen_description" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_description'] : '' ) . '"/><br><br>';
		$html .= '<label for="allergen_icon">' . __( 'Allergen icon', 'allergens-dietary-ictoria' ) . '</label><br>';
		$html .= '<input type="file" name="allergen_icon" id="allergen_icon"><br><br>';
		$html .= '<input type="submit" name="submit" class="button button-primary" value="' . __( 'Add allergen', 'allergens-dietary-ictoria' ) . '" /><br>';
		$html .= '</fieldset>';

		echo $html;
	}


	/**
	 * @param array $data
	 * @brief This method submits the form data to the DB.
	 * @throws Exception if the file is not a valid image
	 * @return void
	 * @author V.B.
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function submit( array $data ) {
		$data = $this->sanitize( $data );

		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergen_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
		}

		( false === Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->checkAllergenExists( $data['allergen_name'] ) ) ?
			Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->addAllergens( $data ) :
			Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->updateAllergens( $data );

		if ( false === wp_check_filetype( $data['allergen_icon']['name'], self::MIME_TYPES ) ) {
			throw new Exception( __( 'The file is not a valid image' ) );
			return;
		} else {

			( false === Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->checkAttachmentExists( $data['allergen_icon']['name'] ) ) ?
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->addAttachment( $data['allergen_icon'] ) :
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->updateAttachment( $data['allergen_icon'] );
		}

		( false === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkAllergyAttachmentExists( $data['allergen_name'] ) ) ?
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->addAllergyAttachment( $data ) :
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->updateAllergyAttachment( $data );
	}

	/**
	 * @param array $data
	 * @brief This method sanitizes the form data for the DB.
	 * @return array $data
	 * @author V.B.
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function sanitize( array $data ) {
		$data['allergen_name']         = sanitize_text_field( wp_unslash( $data['allergen_name'] ) );
		$data['allergen_description']  = sanitize_text_field( wp_unslash( $data['allergen_description'] ) );
		$data['allergen_icon']['name'] = sanitize_file_name( $data['allergen_icon']['name'] );

		return $data;
	}
}
