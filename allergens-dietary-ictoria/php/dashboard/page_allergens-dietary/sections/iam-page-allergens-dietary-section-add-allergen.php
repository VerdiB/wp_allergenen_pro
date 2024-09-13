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
            __('Add Allergen', 'text-domain'),
            str_replace('_', '-', strtolower(__CLASS__)),
            false
        );

        require_once IAM_DIR . '/utilities/database_connect.php';
    }

    public function section_callback()
    {
    }

}
