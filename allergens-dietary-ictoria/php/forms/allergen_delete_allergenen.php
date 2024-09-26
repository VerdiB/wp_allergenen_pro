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

if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

class Allergens_Dietary_Ictoria_Remove_Allergen implements I_Allergens_Dietary_Ictoria_Form
{

	private ?array $_allergen = null;
	private const MIME_TYPES = array('image/png', 'image/jpeg', 'image/jpg');

	public function __construct()
	{
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
		if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
		}
		$this->_allergen = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->getAll_allergensAndAttachments();

		$html = '';

		if (empty($this->_allergen)) {
			$html .= '<p>No allergens found.</p>';
		} else {
			foreach ($this->_allergen as $allergy) {
				$html .= '<fieldset>';
				$html .= '<h1>Allergy: ' . esc_html($allergy->allergy_name) . '</h1>';
				$html .= '<div>Description: ' . esc_html($allergy->allergy_description) . '</div>';
				$html .= '<div>Is Default Option: ' . ($allergy->is_default_option ? 'Yes' : 'No') . '</div>';
				$html .= '<div>Attachment Name: ' . esc_html($allergy->attachment_name) . '</div>';
				$html .= '<div>Attachment Path: ' . esc_html($allergy->attachment_path) . '</div>';
				$html .= '<input type="hidden" name="allergen_name_hidden" value="' . esc_html(!empty($allergy->allergy_name) ? $allergy->allergy_name : '') . '" />';
				$html .= '<input type="submit" name="submit" class="button button-primary" value="' . __('Delete Allergen', 'allergens-dietary-ictoria') . '" />';
				$html .= '<hr>';
				$html .= '</fieldset>';
			}
		}
		echo $html;
	}

	public function submit(array $data)
	{
		error_log('KAAAAAA');
		$data = $this->sanitize($data);

		if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
		}

		if (empty($data['allergen_name_hidden'])) {
			return;
		}

		// Call delete only if a valid allergen name is set
		// if (isset($data['allergen_name_hidden'])) {
			Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->deleteAllergen($data['allergen_name_hidden']);
		// }
	}

	public function sanitize(array $data)
	{
		$data['allergen_name_hidden'] = sanitize_text_field(wp_unslash($data['allergen_name_hidden']));
		return $data;
	}

}

