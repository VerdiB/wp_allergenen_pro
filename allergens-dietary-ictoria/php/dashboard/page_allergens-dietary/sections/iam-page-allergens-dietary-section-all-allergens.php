<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_All_Allergens extends IAM_Base_Section
{
    public static $allergen_data = array();

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('All Allergens', 'allergens-dietary-ictoria'),
            str_replace('_', '-', strtolower(__CLASS__)),
            false
        );

        require_once IAM_DIR . '/utilities/database_connect.php';
    }

    public function section_callback()
    {
        IAM_Database_Connect::get_allergens_with_attachments();
    }

}
