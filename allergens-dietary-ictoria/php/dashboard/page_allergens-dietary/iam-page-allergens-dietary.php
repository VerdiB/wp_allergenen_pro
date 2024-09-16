<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary extends IAM_Base_Page
{
    private static $page_sections = [
        'Add_Allergen',
        'Manage_Allergens',
    ];

    public function __construct()
    {
        parent::__construct(
            'Allergens & Dietary Plugin',
            'Allergens & Dietary',
            'manage_options',
            'iam-allergens-dietary',
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
        echo '<div class="wrap">';

        // <div class="iam-allergens-dietary">
        echo '<div class="' . $this->menu_slug . '">';

        // <h1>Allergens & Dietary Plugin</h1>
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

        // parent::render_sections(true); outputs this:
        //
        // <div class="iam-allergens-dietary-add-allergen">
        //  sections/iam-page-allergens-dietary-section-add-allergen.php-->render_sections()
        //  html gets output here ...
        // </div>
        // <div class="iam-allergens-dietary-manage-allergens">
        //  sections/iam-page-allergens-dietary-section-manage-allergens.php-->render_sections()
        //  html gets output here ...
        // </div>
        parent::render_sections(true);
        // iam-base-page.php->render_sections($enable_header = true) shows the h2 header given in a sections' __construct() second argument ($section_title)

        echo '</h1>';
        echo '</div>';
    }
}
