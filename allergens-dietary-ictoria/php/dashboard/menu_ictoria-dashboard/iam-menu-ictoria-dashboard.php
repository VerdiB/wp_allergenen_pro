<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Menu_Ictoria_Dashboard extends IAM_Base_Page
{
    private static $page_base = 'IAM_Menu_Ictoria_Dashboard';
    private static $page_sections = [
        'Welcome',
    ];

    public function __construct()
    {
        parent::__construct(
            'Ictoria Plugin Dashboard',
            'Ictoria',
            'manage_options',
            'iam-dashboard',
            [$this, 'render_page'],
            'dashicons-admin-settings',
            null, // 56
        );

        foreach (self::$page_sections as $section_class) {
            $class_name = self::$page_base . '_' . $section_class;
            if (class_exists($class_name)) {
                $this->add_section(new $class_name);
            }
        }
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
