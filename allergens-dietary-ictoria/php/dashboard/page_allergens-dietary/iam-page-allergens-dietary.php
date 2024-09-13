<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary extends IAM_Base_Page
{
    private static $page_sections = [
        'Add_Allergen',
    ];

    public function __construct()
    {
        parent::__construct(
            'Allergens & Dietary Plugin',
            'Allergens & Dietary',
            'manage_options',
            'allergens-dietary-add-allergen',
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

    public function render_page()
    {
        if (!current_user_can($this->capability)) {
            return;
        }

        echo '<div class="wrap">';
        echo '<div class="' . $this->menu_slug . '">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        echo '<form action="options.php" method="post">';
        settings_fields($this->menu_slug . '_options_group');
        do_settings_sections($this->menu_slug);
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
}
// $options = Allergens_Dietary_Ictoria_Functions::get_options();
