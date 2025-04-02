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
if (!enum_exists('FormType')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/lists/form_type.php';
}

if (!class_exists('Allergens_Dietary_Form')) {
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/forms/allergen_form.php';
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
	
	public function __construct(bool $isTable = false)
	{
		// #[\Override]
		parent::__construct();
		if (FormType::ALLERGENS === self::$_formType) {
			self::$_formObject = new Allergens_Dietary_Pro_Allergen_Form();
		}
		if (FormType::UPDATE === self::$_formType) {
			self::$_formObject = new Allergens_Dietary_Pro_Update_Allergen_Form();
		}
	}

	public function showForm(string $allergenName = null){
		if ( !empty( $_POST['allergenForm'] ) ){
			$this->_formData = $_POST['allergenForm']; 
		}

		if ( !empty( $_FILES['allergenFormFile'] ) ){
			$this->_formData = array_merge($this->_formData , $_FILES);
		}
		
		parent::showForm($allergenName);
	}
}
