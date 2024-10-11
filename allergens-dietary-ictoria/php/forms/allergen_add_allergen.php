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
	private const MIME_TYPES = array( 'image/png', 'image/jpeg', 'image/jpg' );

	public function __construct() {
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

		if ( ! is_null( $allergenName ) ) {
			// TODO: Implement showForm() method. when the allergen name is not null
			if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergy_Attachment_Queries' ) ) {
				require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
			}
			$this->_allergen = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->getAllergyAttachment( $allergenName );
		}

		$html  = '<fieldset>';
		$html .= '<input type="hidden" name="allergen_name_hidden" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_name'] : '' ) . '"/>';
		$html .= '<label for="allergen_name">' . __( 'Allergen name', 'allergens-dietary-ictoria' ) . '</label><br>';
		$html .= '<input type="text" name="allergen_name" id="allergen_name" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_name'] : '' ) . '" required/><br><br>';
		$html .= '<label for="type">' . __( 'Type', 'allergens-dietary-ictoria' ) . '</label> <br/>';
		$html .= '<select name="type" id="type" required>';
		$html .= self::do_dropdown();
		$html .= '</select> <br><br>';
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

		if ( empty( $data['allergen_name_hidden'] ) ) {
			try{
				Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->addAllergens( $data );
			} catch ( Exception $e ) {
				_e('Allergen already exists', 'allergens-dietary-ictoria');
			}
		} else {
			Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->updateAllergens( $data );
		}

		if ( false === wp_check_filetype( $data['allergen_icon']['name'], self::MIME_TYPES ) ) {
			throw new Exception( __( 'The file is not a valid image' ) );
			return;
		} else {

			( empty( $this->_allergen['attachment_name'] ) && false === Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->checkAttachmentExists( $data['allergen_icon']['name'] ) ) ?
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->addAttachment( $data['allergen_icon'] ) :
				// Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->updateAttachment( $data['allergen_icon'], (string)$this->_allergen['attachment_name'] );
				_e( 'Attachment already exists', 'allergens-dietary-ictoria' );
		}

		if ( ! isset( $data['allergen_name_hidden'] ) || $data['allergen_name_hidden'] === '' && false === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkAllergyAttachmentExists( $data['allergen_name'] ) ) {
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->addAllergyAttachment( $data );
		} elseif ( ! empty( $data['allergen_name_hidden'] ) && $data['allergen_name_hidden'] !== $data['allergen_name'] && true === Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->checkAllergenExists( $data['allergen_name'] ) ) {
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->updateAllergyAttachment( $data, $data['allergen_name_hidden'] );
		}

		// $this->_allergen = null;
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
		$data['type']                  = absint( sanitize_text_field( wp_unslash( $data['type'] ) ) );
		$data['allergen_icon']['name'] = sanitize_file_name( $data['allergen_icon']['name'] );

		if ( $data['type'] > 1 || $data['type'] < 0 ) {
			$data['type'] = 1;
		}

		return $data;
	}

	private function do_dropdown() {
		$html = '';
		if ( empty( $this->_allergen ['is_allergy'] ) ) {
			$html  = '<option value="1" selected>' . __( 'Allergy', 'allergens-dietary-ictoria' ) . '</option>';
			$html .= '<option value="0">' . __( 'Dietary restriction', 'allergens-dietary-ictoria' ) . '</option>';
			return $html;
		}

		if ( $this->_allergen['is_allergy'] == 1 ) {
			$html  = '<option value="1" selected>' . __( 'Allergy', 'allergens-dietary-ictoria' ) . '</option>';
			$html .= '<option value="0">' . __( 'Dietary restriction', 'allergens-dietary-ictoria' ) . '</option>';
		} else {
			$html  = '<option value="1">' . __( 'Allergy', 'allergens-dietary-ictoria' ) . '</option>';
			$html .= '<option value="0" selected>' . __( 'Dietary restriction', 'allergens-dietary-ictoria' ) . '</option>';
		}
		return $html;
	}
}
