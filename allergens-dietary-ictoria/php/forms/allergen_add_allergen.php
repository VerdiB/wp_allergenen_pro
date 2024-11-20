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


if (!class_exists('Allergens_Dietary_Ictoria_Notices')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/notice/notice.php';
}
/********************************************************************/
/********************************************************************/

class Allergens_Dietary_Ictoria_Allergen_Form implements I_Allergens_Dietary_Ictoria_Form
{


	private ?array $_allergen = null;
	private array $MIME_TYPES;
	private array $MIME_NAMES;
	private static string $message = '';

	public function __construct(bool $isTable = false)
	{
		$this->MIME_TYPES = Mime_Types::get_mime_types();
		$this->MIME_NAMES = array_map(fn($case) => $case->name, Mime_Types::cases());
		if (false === $isTable){
			if (! empty(self::$message) || self::$message != ''){

				$notice = Allergens_Dietary_Ictoria_Notices::getInstance();
				$notice->display_admin_notice(Notice_Types::ERROR, __(self::$message, 'allergens-dietary-ictoria'));		
				self::$message = '';
			}
			Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();
		}
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
			$html .= '<div class="item" style="display: flex; align-items: center; gap: 15px;">';
			$html .= '<img class="update_ allergen_icon_img" style="height: 75px;" disabled id="allergen_icon_img" src="' . ((!empty($this->_allergen)) ? $this->_allergen['attachment_path'] : "") . '" alt="' . ((!empty($this->_allergen)) ? $this->_allergen['attachment_name'] : "") . '">';
			$html .= '<label class="label-quick-edit">';
			$html .= '<input type="file" class="update_ allergen_icon_file_input" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" name="allergen_icon" id="allergen_icon_file_input" disabled>';
			$html .= '<input type="hidden" class="update_" name="allergen_icon_hidden" value="' . ((!empty($this->_allergen)) ? $this->_allergen['attachment_name'] : "") . '" disabled>';
			$html .= '<span>Set image</span>';
			$html .= '</label>';
			$html .= '</div>';
			$html .= '</fieldset>';
		} else {
			$html = '<fieldset>';
			$html .= '<label for="allergen_name">' . __('Allergen name', 'allergens-dietary-ictoria') . '</label><br>';
			$html .= '<input type="text" name="allergen_name" id="allergen_name" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') . '" required/><br><br>';
			$html .= '<label for="type">' . __('Type', 'allergens-dietary-ictoria') . '</label> <br/>';
			$html .= '<select name="type" id="type" required>';
			$html .= self::do_dropdown();
			$html .= '</select> <br><br>';
			$html .= '<label for="allergen_description">' . __('Allergen description', 'allergens-dietary-ictoria') . '</label><br>';
			$html .= '<input type="text" name="allergen_description" id="allergen_description" value="' . ((!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '') . '"/><br><br>';
			$html .= '<label for="allergen_icon">' . __('Allergen icon', 'allergens-dietary-ictoria') . '</label><br>';
			$html .= '<input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" name="allergen_icon"><br><br>';
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

		$file_type_input = wp_check_filetype($data['allergen_icon']['name']);
		$valid_icon = in_array($file_type_input['type'], $this->MIME_TYPES) ? true : false;
		$showOnPage = "allergens-dietary-add-allergen";
		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
		$empty_file_input = empty($data['allergen_icon']['name']) ? true : false;

		// DB Query's
		$all_query = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();
		$att_query = Allergens_Dietary_Ictoria_Attachment_Queries::getInstance();
		$all_att_query = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance();

		$attachment_exists = $att_query->checkAttachmentExists($data['allergen_icon']['name']);

		// This is temporary, should be WP conform errors!'
		if (empty($data) || !isset($data)) {
			// $notice = Allergens_Dietary_Ictoria_Notices::getInstance();
			// $notice->display_admin_notice(Notice_Types::ERROR, __('Form has not been set!', 'allergens-dietary-ictoria'));
		
			return;
		} elseif (empty($data['allergen_name'])) {
			$notice = Allergens_Dietary_Ictoria_Notices::getInstance();
			$notice->display_admin_notice(Notice_Types::ERROR, __('Allergen Name Can\'t be empty or blank!', 'allergens-dietary-ictoria'));
			return;
		}

		// ADD ALLERGEN PAGE!
		if (!isset($data['allergen_name_hidden']) && $page === $showOnPage) {

			$no_icon_selected = false;

			if ($all_query->checkAllergenExists($data['allergen_name'])) {
				$notice = Allergens_Dietary_Ictoria_Notices::getInstance();
				$notice->display_admin_notice(Notice_Types::ERROR, __('Allergen name already exists', 'allergens-dietary-ictoria'));
				
				return;
			}

			// Default image
			if ($empty_file_input) {
				$imagePath = get_home_url() . '/wp-content/plugins/allergens-dietary-ictoria/assets/icons/no_icon_selected.png';
				$imageName = sanitize_file_name(basename($imagePath));
				$data['allergen_icon'] = [
					'name' => $imageName,
					'tmp_name' => $imagePath,
					'type' => 'image/png',
				];
				$no_icon_selected = true;
			}

			// This is temporary, should be WP conform errors!
			if (!$valid_icon && !$no_icon_selected) {
				self::$message = 'The file is not a valid image. Supported image types are: ' . implode(', ', $this->MIME_NAMES) . '.';
				return;
			}

			$all_query->addAllergens($data);
			if (!$no_icon_selected && !$attachment_exists) { // Don't add attachment to the table, image already exists there.
				$att_query->addAttachment($data['allergen_icon']);
			}
			$all_att_query->addAllergyAttachment($data);

			// Temporary feedback, should be of WP conform.
			self::$message = 'Succesfully added new allergen: ' . $data['allergen_name'] . '.';
		} else { // QUICK EDIT PAGE!
			// This is temporary, should be WP conform errors!
			if ($all_query->checkAllergenExists($data['allergen_name']) && $data['allergen_name_hidden'] !== $data['allergen_name']) {
				self::$message = 'Allergen name already exists';
				return;
			}
			if (!$valid_icon && !$empty_file_input) {
				self::$message = 'The file is not a valid image. Supported image types are: ' . implode(', ', $this->MIME_NAMES) . '.';
				return;
			}

			$all_query->updateAllergens($data);
			if ($empty_file_input) {
				return;
			} // update only the new allergen data when not uploading a new image. name, description etc.
			

			if ($attachment_exists) { // if the attachment exists, set to existing img and only remove attachment when not used.
				$all_att_query->updateAllergyAttachment($data['allergen_name_hidden'], $data['allergen_icon']['name']);
				if ($data['allergen_icon_hidden'] !== 'no_icon_selected.png' && !$all_att_query->attachmentIsUsed($data['allergen_icon_hidden'])) {
					$att_query->deleteAttachment($data['allergen_icon_hidden']);
				}
			} else {
				// Prevent losing no_icon_selected.png as image in DB, and if there are multiple of the old img don't change all of them.
				if ($data['allergen_icon_hidden'] === 'no_icon_selected.png' || $all_att_query->checkMultipleAttachmentsExists($data['allergen_icon_hidden'])) {
					$att_query->addAttachment($data['allergen_icon']);
					$all_att_query->updateAllergyAttachment($data['allergen_name_hidden'], $data['allergen_icon']['name']);
				} else {
					$att_query->updateAttachment($data['allergen_icon'], $data['allergen_icon_hidden']);
				}
			}
		}
	}

	/**
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
		$data['type'] = absint(sanitize_text_field(wp_unslash($data['type'])));
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
