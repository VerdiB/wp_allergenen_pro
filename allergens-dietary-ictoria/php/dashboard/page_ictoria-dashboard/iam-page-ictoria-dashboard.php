<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Ictoria_Dashboard extends IAM_Base_Page
{
    // add page sections here
    private static $page_sections = [
        'Welcome',
        'Another_One',
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
            $class_name = __CLASS__ . '_Section_' . $section_class;
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
