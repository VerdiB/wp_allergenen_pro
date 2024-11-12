<?php

// exit if user can access this file directly
if (!defined('ABSPATH')) {
	exit;
}

if (!interface_exists('I_Allergens_Dietary_Ictoria_Form')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/Iallergen_form.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Attachment_Queries')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
}

/**
 * @brief This shows the tabs on add/update allergens .
 * @author T.K.
 * @since 1.0.0
 * @date 18-9-2024
 */
/**
 * @brief This shows the tabs on add/update allergens .
 * @author T.K.
 * @since 1.0.0
 * @date 18-9-2024
 */

if (!class_exists('Allergens_Dietary_Ictoria_Tabs')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
}
if (!enum_exists('Mime_Types')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/lists/mime_types.php';
}

/********************************************************************/
/********************************************************************/

class Allergens_Dietary_Ictoria_Allergen_Form implements I_Allergens_Dietary_Ictoria_Form
{


	private ?array $_allergen = null;
	private array $MIME_TYPES;
	private array $MIME_NAMES;

	public function __construct()
	{
		$this->MIME_TYPES = Mime_Types::get_mime_types();
		$this->MIME_NAMES = array_map(fn($case) => $case->name, Mime_Types::cases());
	}


	/**
	 * @param string|null $allergenName
	 * @brief This method shows the form to add/update allergens .
	 * @return void
	 * @author V.B.
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function showForm(?string $allergenName = null)
	{

		if (!is_null($allergenName)) {
			// TODO: Implement showForm() method. when the allergen name is not null
			$this->_allergen = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->getAllergyAttachment($allergenName);
		}

		$showOnPage = ["allergens-dietary-show-allergens"];

		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

		if (in_array($page, $showOnPage, true)) {
			$html = '<fieldset class="update_form">';
			$html .= '<div class="form-column">';
			$html .= '<input disabled type="hidden" class="update_" name="allergen_name_hidden" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') . '"/>';
			$html .= '<label for="allergen_name">' . __('Allergen name', 'allergens-dietary-ictoria') . '</label>';
			$html .= '<input type="text" class="update_" name="allergen_name" id="allergen_name" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') . '" disabled required/>';
			$html .= '<div class="dropdown-row">';
			$html .= '<label for="type">' . __('Type', 'allergens-dietary-ictoria') . '</label>';
			$html .= '<select name="type" id="type" class="type">';
			$html .= self::do_dropdown();
			$html .= '</select>';
			$html .= '</div>';
			$html .= '<input disabled type="submit" class="update_" name="submit" class="button button-primary" value="' . __('Update', 'allergens-dietary-ictoria') . '"/>';
			$html .= '</div>';
			$html .= '<label for="allergen_description">' . __('Allergen description', 'allergens-dietary-ictoria') . '</label>';
			$html .= '<input type="text" class="update_" name="allergen_description" id="allergen_description" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '') . '" disabled/>';
			$html .= '<div style="display: flex; align-items: center; gap: 15px;">';
			$html .= '<img style="height: 75px;" id="allergen_icon_img" src="' . ((!empty($this->_allergen)) ? $this->_allergen['attachment_path'] : "") . '" alt="' . ((!empty($this->_allergen)) ? $this->_allergen['attachment_name'] : "") . '">';
			$html .= '<label class="label-quick-edit">';
			$html .= '<input type="file" class="update_" name="allergen_icon" id="allergen_icon_file_input" disabled>';
			$html .= '<span>Set image</span>';
			$html .= '</label>';
			$html .= '</div>';
			$html .= '</fieldset>';
		} else {
			$html = '<fieldset>';
			$html .= '<input type="hidden" name="allergen_name_hidden" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') . '"/>';
			$html .= '<label for="allergen_name">' . __('Allergen name', 'allergens-dietary-ictoria') . '</label><br>';
			$html .= '<input type="text" name="allergen_name" id="allergen_name" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') . '" required/><br><br>';
			$html .= '<label for="type">' . __('Type', 'allergens-dietary-ictoria') . '</label> <br/>';
			$html .= '<select name="type" id="type" required>';
			$html .= self::do_dropdown();
			$html .= '</select> <br><br>';
			$html .= '<label for="allergen_description">' . __('Allergen description', 'allergens-dietary-ictoria') . '</label><br>';
			$html .= '<input type="text" name="allergen_description" id="allergen_description" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '') . '"/><br><br>';
			$html .= '<label for="allergen_icon">' . __('Allergen icon', 'allergens-dietary-ictoria') . '</label><br>';
			$html .= '<input type="file" name="allergen_icon"><br><br>';
			$html .= '<input type="submit" name="submit" class="button button-primary" value="' . __('Add allergen', 'allergens-dietary-ictoria') . '"/><br>';
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
	public function submit(array $data)
	{
		$data = $this->sanitize($data);
		$file_info = wp_check_filetype($data['allergen_icon']['name']);
		$valid_icon = in_array("image/" . $file_info['ext'], $this->MIME_TYPES) ? true : false;
		$table_al_at = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance();
		$table_at = Allergens_Dietary_Ictoria_Attachment_Queries::getInstance();
		$table_al = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();
		$att_exists = $table_at->checkAttachmentExists($data['allergen_icon']['name']);
		$al_exists = $table_al->checkAllergenExists($data['allergen_name']);
		$al_at_exists = $table_al_at->checkAllergyAttachmentExists($data['allergen_name_hidden']);
		
		if (!empty($data['allergen_name_hidden'])) {
			$mlpleatt_exists = $table_al_at->checkMultipleAttachmentsExists($data['allergen_name']);
		}
		if (!empty($data['allergen_name_hidden'])) {
			if ($data['allergen_name_hidden'] !== $data['allergen_name']) {
				if (false !== $al_exists) {
					return;
				}
			}
		}
		if (false === $att_exists) {
			if (empty($data['allergen_name_hidden'])) {
				$file_info = wp_check_filetype($data['allergen_icon']['name']);

				//Adding attachment for add allergen
				if ($valid_icon == true) {
					$table_at->addAttachment($data['allergen_icon']);
				}

			}
		}
		if (!empty($data['allergen_name_hidden'])) {
			$allergen_icon = $table_al_at->find_allergy($data['allergen_name_hidden']);
		}
		$multiple_attachment = "no_access";

		//update for update allergen and add for add allergen
		if (empty($data['allergen_name_hidden'])) {
			if (false === $al_exists) {
				$table_al->addAllergens($data);
				echo '<div style="background-color: limegreen; max-width: 270px;">';
				echo __("Successfully added new Allergen/Diet", 'allergens-dietary-ictoria') . '</div><br>';
			} else {
				echo '<div style="background-color: orange; max-width: 270px;">';
				echo __("Allergen already exists", 'allergens-dietary-ictoria') . '</div><br>';
				return;
			}
		} else {
			$table_al->updateAllergens($data);

			//check attachment amount
			if (true === $al_at_exists && true === $mlpleatt_exists) {
				$multiple_attachment = "access";
			} elseif (false === $mlpleatt_exists && true === $al_at_exists) {
				$multiple_attachment = "no_multiple_attachment_access";
			} else {
				$multiple_attachment = "no_access";
			}
			if (false === $att_exists) {
				if ($valid_icon === true){
					$table_at->addAttachment($data['allergen_icon']);
				}else{
					return;
				}
			}
		$multiple_attachment !== "no_access" ? $table_al_at->updateAllergyAttachment($data['allergen_icon']['name'], $data['allergen_name']) : $multiple_attachment = "no_access";
		}

		$add_att_exists = $table_at->checkAttachmentExists($data['allergen_icon']['name']);
		$add_al_exists = $table_al->checkAllergenExists($data['allergen_name']);

		//those if-statements create the connection between the allergen and the attachment
		if (empty($data['allergen_name_hidden']) && true !== $al_at_exists) {
			if (true === $add_al_exists && true === $add_att_exists) {
				$table_al_at->addallergyAttachment($data);
			}
		} else {
			$mlpleoldatt_exists = $table_al_at->checkMultipleAttachmentsExists($allergen_icon);
		}

		//access if-statements
		if ($multiple_attachment == "access" && $multiple_attachment !== "no_access") {
			if (false === $att_exists) {
				$table_at->updateAttachment($data['allergen_icon'], $allergen_icon);
			}
			if (false === $mlpleoldatt_exists) {
				$table_at->deleteAttachment($allergen_icon);
			}
		} elseif ($multiple_attachment !== "no_access") {
			if (false === $mlpleoldatt_exists) {
				$table_at->deleteAttachment($allergen_icon);
			}
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
	public function sanitize(array $data)
	{
		$data['allergen_name'] = sanitize_text_field(wp_unslash($data['allergen_name']));
		$data['allergen_description'] = sanitize_text_field(wp_unslash($data['allergen_description']));
		if (isset($data['type'])) {
			$data['type'] = absint(sanitize_text_field(wp_unslash($data['type'])));
		}
		$data['allergen_icon']['name'] = sanitize_file_name($data['allergen_icon']['name']);

		return $data;
	}
	private function do_dropdown()
	{
		$html = '';

		$ShowOnPage = ["allergens-dietary-add-allergen"];
		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

		if (in_array($page, $ShowOnPage, true)) {
			if (empty($this->_allergen['is_allergy'])) {
				$html = '<option value="1" selected>' . __('Allergy', 'allergens-dietary-ictoria') . '</option>';
				$html .= '<option value="0">' . __('Dietary restriction', 'allergens-dietary-ictoria') . '</option>';
				return $html;
			}
		}

		if (!empty($this->_allergen)) {
			if ($this->_allergen['is_allergy'] == 1) {
				$html = '<option value="1" class="option" selected disabled>' . __('Allergy', 'allergens-dietary-ictoria') . '</option>';
				$html .= '<option value="0" class="option" disabled>' . __('Dietary restriction', 'allergens-dietary-ictoria') . '</option>';
			} else {
				$html = '<option value="1" class="option" disabled>' . __('Allergy', 'allergens-dietary-ictoria') . '</option>';
				$html .= '<option value="0" class="option" selected disabled>' . __('Dietary restriction', 'allergens-dietary-ictoria') . '</option>';
			}
		}
		return $html;
	}
}
