<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Settings extends IAM_Base_Page
{
    private static $page_sections = [
        'Welcome',
        'Another_One',
    ];

    public function __construct()
    {
        parent::__construct(
            'Settings',
            'Settings',
            'manage_options',
            'iam-settings',
            [$this, 'render_page'],
            '',
            null,
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
        return false;
    }

    protected function get_parent_slug()
    {
        return 'iam-dashboard';
    }
}
