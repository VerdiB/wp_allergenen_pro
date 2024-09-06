<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Settings_Page extends ADI_Base_Menu_Page
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
            'Ictoria Plugin Settings',
            'Settings',
            'manage_options',
            'adi-dashboard-settings',
            [$this, 'render_page'],
            '',
            null
        );

        $this->add_section(new ADI_Dashboard_Settings_Section());
    }

    protected function is_top_level()
    {
        return false;
    }

    protected function get_parent_slug()
    {
        return 'adi-dashboard';
    }
}
