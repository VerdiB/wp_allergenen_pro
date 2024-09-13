<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_Landing extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Allergens & Dietary Plugin', 'text-domain'),
            str_replace('_', '-', strtolower(__CLASS__))
        );

        require_once IAM_DIR . '/utilities/database_connect.php';
    }

    public function section_callback()
    {
        $allergens = IAM_Database_Connect::get_allergens();
        foreach ($allergens as $allergen) {
            echo '<p>' . $allergen->allergy_name . '</br>';
            echo $allergen->allergy_description . '</p>';
        }
    }

}
