<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Page extends ADI_Base_Menu_Page
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
            'Ictoria',
            'manage_options',
            'adi-dashboard',
            [$this, 'render_page'],
            'dashicons-admin-settings',
            56
        );

        $this->add_section(new ADI_Dashboard_Main_Section());
    }

    protected function is_top_level()
    {
        return true;
    }

    protected function get_parent_slug()
    {
        return ''; // No parent since it's top-level
    }
}
