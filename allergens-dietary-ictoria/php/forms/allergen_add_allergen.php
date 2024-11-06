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
	private const MIME_TYPES = array( 'png', 'jpeg', 'jpg' );

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

		$showOnPage = ["allergens-dietary-show-allergens"];

		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

		if (in_array($page, $showOnPage, true)) {
			$html  = '<fieldset class="update_form">';
			$html .= '<div class="form-column">';
			$html .= '<input disabled type="hidden" class="update_" name="allergen_name_hidden" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_name'] : '' ) . '"/>';
			$html .= '<label for="allergen_name">' . __( 'Allergen name', 'allergens-dietary-ictoria' ) . '</label>';
			$html .= '<input type="text" class="update_" name="allergen_name" id="allergen_name" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_name'] : '' ) . '" disabled required/>';
			$html .= '<div class="dropdown-row">';
			$html .= '<label for="type">' . __( 'Type', 'allergens-dietary-ictoria' ) . '</label>';
			$html .= '<select name="type" id="type" class="type">';
			$html .= self::do_dropdown();
			$html .= '</select>';
			$html .= '</div>';
			$html .= '<input disabled type="submit" class="update_" name="submit" class="button button-primary" value="' . __( 'Update', 'allergens-dietary-ictoria' ) . '"/>';
			$html .= '</div>';
			$html .= '<label for="allergen_description">' . __( 'Allergen description', 'allergens-dietary-ictoria' ) . '</label>';
			$html .= '<input type="text" class="update_" name="allergen_description" id="allergen_description" value="' . ( ( ! empty( $this->_allergen ) ) ? $this->_allergen['allergy_description'] : '' ) . '" disabled/>';
			$html .= '<div>';
			$html .= '<label for="allergen_icon">' . __( 'Allergen icon', 'allergens-dietary-ictoria' ) . '</label>';
			$html .= '<input type="file" class="update_" name="allergen_icon" id="allergen_icon" required disabled>';
			$html .= '</div>';
			$html .= '</fieldset>';
		}else{
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
			$html .= '<input type="file" name="allergen_icon"><br><br>';
			$html .= '<input type="submit" name="submit" class="button button-primary" value="' . __( 'Add allergen', 'allergens-dietary-ictoria' ) . '"/><br>';
		}
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
	if (isset($data['type'])){

		$text = "Successfully added new Allergen/Diet";
		$data = $this->sanitize( $data );
		$ShowOnPage = ["allergens-dietary-add-allergen"];
		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergen_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
		}

		if (Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->checkAllergenExists($data['allergen_name'])) {
			return;
		}

		if ( empty( $data['allergen_name_hidden'] ) ) {
			$file_info = wp_check_filetype( $data['allergen_icon']['name'] );

			//Adding attachment for add allergen
			if (false === in_array($file_info["ext"], self::MIME_TYPES)){
				return;
			} else {
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->addAttachment( $data['allergen_icon'] );
			}
		}

		//update for update allergen and add for add allergen
		if ( empty( $data['allergen_name_hidden'] ) ) {
				if (false === Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->checkAllergenExists( $data['allergen_name'] )){
					Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->addAllergens( $data );
					echo '<div style="background-color: limegreen; max-width: 270px;">';
					$text = __("Successfully added new Allergen/Diet", 'allergens-dietary-ictoria');
				}else{
					echo '<div style="background-color: orange; max-width: 270px;">';
					$text = __("Allergen already exists", 'allergens-dietary-ictoria');
				}
		} else {
				Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->updateAllergens( $data );
		}

	//The attachment of the allergen before the update
	$allergen_icon = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->find_allergy($data['allergen_name']);
	$multiple_attachment = "no_access";

	//check attachment amount
	if (!empty($data['allergen_name_hidden'])){
		if (true === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkAllergyAttachmentExists( $data['allergen_name'], $data['allergen_icon']['name']) &&
			true === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkMultipleAttachmentsExists($data['allergen_icon']['name'])){
			$multiple_attachment = "access";
		}elseif(false === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkMultipleAttachmentsExists($data['allergen_icon']['name']) &&
			true === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkAllergyAttachmentExists( $data['allergen_name'], $data['allergen_icon']['name'])){
			$multiple_attachment = "no_multiple_attachment_access";
		}else{
			$multiple_attachment = "no_access";
		}
	}

	//those if-statements create the connection between the allergen and the attachment
	if (empty($data['allergen_name_hidden']) && true !== Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkAllergyAttachmentExists( $data['allergen_name'], $data['allergen_icon']['name'] ) ) {
		if (true === Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->checkAllergenExists( $data['allergen_name']) &&
		true === Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->checkAttachmentExists($data['allergen_icon']['name'])){
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->addallergyAttachment( $data );
		}
	}

		//access if-statements
		if ($multiple_attachment == "access" && $multiple_attachment !== "no_access"){
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->updateallergyAttachment( $data['allergen_icon']['name'], $data["allergen_name_hidden"] );
			if (false === Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->checkAttachmentExists( $data['allergen_icon']['name'] )){
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->updateAttachment( $data['allergen_icon'],  $allergen_icon);
			}
			if (false == Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkMultipleAttachmentsExists($allergen_icon)){	
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->deleteAttachment($allergen_icon);
			}
		}elseif($multiple_attachment !== "no_access" && false === Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->checkAttachmentExists( $data['allergen_icon']['name'] )){
			$file_info = wp_check_filetype( $data['allergen_icon']['name'] );
			if (false === in_array($file_info["ext"], self::MIME_TYPES)){
				return;
			} else {
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->addAttachment( $data['allergen_icon'] );
			}
			Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->updateallergyAttachment( $data['allergen_icon']['name'], $data["allergen_name"] );
			if (false == Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkMultipleAttachmentsExists($allergen_icon)){	
				Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->deleteAttachment($allergen_icon);
			}
		}
	
	//refresh for update or a notification for add allergen
		if (in_array($page, $ShowOnPage, true)) {
			echo $text;
		}else{
			wp_redirect( admin_url( 'admin.php?page=allergens-dietary-show-allergens' ) );
		}

		echo '</div><br>';
	}else{
		wp_redirect( admin_url( 'admin.php?page=allergens-dietary-show-allergens' ) );
	}
}

	/**allergen_name
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
		if (isset($data['type'])){
			$data['type'] = absint( sanitize_text_field( wp_unslash( $data['type'] ) ) );
		}
		$data['allergen_icon']['name'] = sanitize_file_name( $data['allergen_icon']['name'] );

		return $data;
	}

	private function do_dropdown() {
		$html = '';

		$ShowOnPage = ["allergens-dietary-add-allergen"];
		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

	if (in_array($page, $ShowOnPage, true)) {
		if ( empty( $this->_allergen ['is_allergy'] ) ) {
			$html  = '<option value="1" selected>' . __( 'Allergy', 'allergens-dietary-ictoria' ) . '</option>';
			$html .= '<option value="0">' . __( 'Dietary restriction', 'allergens-dietary-ictoria' ) . '</option>';
			return $html;
		}
	}

	if (!empty($this->_allergen)){
		if ( $this->_allergen['is_allergy'] == 1 ) {
			$html  = '<option value="1" class="option" selected disabled>' . __( 'Allergy', 'allergens-dietary-ictoria' ) . '</option>';
			$html .= '<option value="0" class="option" disabled>' . __( 'Dietary restriction', 'allergens-dietary-ictoria' ) . '</option>';
		} else {
			$html  = '<option value="1" class="option" disabled>' . __( 'Allergy', 'allergens-dietary-ictoria' ) . '</option>';
			$html .= '<option value="0" class="option" selected disabled>' . __( 'Dietary restriction', 'allergens-dietary-ictoria' ) . '</option>';
		}
	}
		return $html;
	}
}
