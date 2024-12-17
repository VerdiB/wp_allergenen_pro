<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!interface_exists('I_Allergens_Dietary_Pro_Form')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/Iallergen_form.php';
}

if (!class_exists('Allergens_Dietary_Pro_License_Form')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form_license.php';
}

if (!class_exists('Allergens_Dietary_Pro_Allergen_Form')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_add_allergen.php';
}
if (!class_exists('Allergens_Dietary_Pro_Update_Allergen_Form')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_update_allergen.php';
}

if (!class_exists('Allergens_Dietary_Form')) {
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/forms/allergen_form.php';
}
if (!enum_exists('FormType')) {
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/lists/form_type.php';
}


/**
 * @class Allergens_Dietary_Pro_Form
 * @brief This class is a singleton strategy
 * that creates a form for the allergens and dietary restrictions plugin.
 * @author Ictoria
 * @date 2-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Pro_Form extends Allergens_Dietary_Form
{
	
	protected function __construct(bool $isTable = false)
	{
		parent::__construct();
		if (FormType::ALLERGENS === self::$_formType) {
			self::$_formObject = new Allergens_Dietary_Pro_Allergen_Form();
		}
		if (FormType::UPDATE === self::$_formType) {
			self::$_formObject = new Allergens_Dietary_Pro_Update_Allergen_Form();
		}
	}

	public static function getInstance(bool $isTable = false)
	{
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function setFormType(FormType $formType)
	{
		self::$_formType = $formType;
	}

	public static function getFormType()
	{
		return self::$_formType;
	}

	public function submitUpdate()
	{

		if (!empty($_POST)) {
			$_data = $_POST;
		}

		if (!empty($_FILES)) {
			$_data = array_merge($_data, $_FILES);
		}

		if (!empty($_POST['submit'])) {
			self::$_formObject->submit($_data);
		}
	}

	public function showForm(string $allergenName = null)
	{

		$showOnPage = ["allergens-dietary-show-allergens"];
		$showOnPageSecondOption = ["allergens-dietary-add-allergen"];

		$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';

		if (!empty($_POST)) {
			$_data = $_POST;
		}

		if (!empty($_FILES)) {
			$_data = array_merge($_data, $_FILES);
		}

		if (!in_array($page, $showOnPage, true)) {
			if (!empty($_POST['submit'])) {
				self::$_formObject->submit($_data);
			}
		}

		if (in_array($page, $showOnPage, true)) {
			echo '<div class="allergens_table_form" style="display: none;" id="' . $allergenName . '_form">';
			self::$_formObject->showForm($allergenName);
			echo '</div>';
		} else {
			if (in_array($page, $showOnPageSecondOption, true)) {
				echo '<div class="allergens_form health-check-body"><form action="" method="post" style="max-width: 350px;" enctype="multipart/form-data" class="add_allergens_form">';
				self::$_formObject->showForm($allergenName);
				echo '</form></div>';
			}else{
				echo '<div class="allergens_form health-check-body"><form action="" method="post" enctype="multipart/form-data" class="add_allergens_form">';
				self::$_formObject->showForm($allergenName);
				echo '</form></div>';
			}
		}
	}
}
