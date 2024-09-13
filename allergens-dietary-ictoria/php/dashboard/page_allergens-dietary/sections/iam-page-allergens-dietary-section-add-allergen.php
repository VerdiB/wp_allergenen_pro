<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_Add_Allergen extends IAM_Base_Section
{

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Add Allergen', 'allergens-dietary-ictoria'),
            str_replace('_', '-', strtolower(__CLASS__)),
            false
        );
    }

    public function section_callback()
    {
        if (!class_exists('Allergens_Dietary_Ictoria_Form')) {
            require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
        }
        Allergens_Dietary_Ictoria_Form::setFormType(FormType::ALLERGENS);
        Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
    }

}
