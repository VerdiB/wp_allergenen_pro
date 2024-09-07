<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('IAM_MENU_ICTORIA_DASHBOARD_DIR', __DIR__);

class IAM_Menu_Ictoria_Dashboard extends IAM_Base_Page
{
    public function __construct()
    {
        parent::__construct(
            'Ictoria Plugin Dashboard',
            'Ictoria',
            'manage_options',
            'iam-dashboard',
            [$this, 'render_page'],
            'dashicons-admin-settings',
            null// 56
        );

        // $this->add_section(new ADI_Dashboard_Main_Section());
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
