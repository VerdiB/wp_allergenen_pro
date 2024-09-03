<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Page extends ADI_Base_Dashboard_Page
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct(
            'Ictoria Plugin Dashboard',
            'Ictoria Plugins',
            'manage_options',
            'allergens-dietary-ictoria',
            [$this, 'render_page'],
            'dashicons-admin-settings',
            56
        );

        // Initialize section within this page
        // $section = ADI_Dashboard_Main_Section::instance();
        $section = ADI_Dashboard_Main_Section::instance();
    }

    public function validate_settings($input)
    {
        // Custom validation logic if needed
        return $input;
    }
}
