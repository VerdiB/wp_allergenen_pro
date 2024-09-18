<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_New_Page_Title extends IAM_Base_Page
{
    // $page_sections = ['Do_Not', 'Forget_To', 'Import_Sections', 'Like_This' ];
    private static $page_sections = [];

    public function __construct()
    {
        parent::__construct(
            'New Page h1 Title', /* page h1 title */
            'Page submenu Title', /* submenu title/label */
            'manage_options', 
            'iam-new-page-title', /* page slug */
            [$this, 'render_page'], /* html output callback function */
            '', /* these are for top-level so just use: '' */
            null, /* these are for top-level so just use: null */
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
        return 'iam-dashboard'; /* Top-level slug */
    }

    public function render_page()
    {
        echo '<div class="wrap">';

        /* you can change things inside this to change the page wrapper etc.
         * render_sections can also be overwritten with a custom function that 
         * outputs different HTML. Make sure to check the IAM_Base_Page before. 
         */
        echo '<div class="' . $this->menu_slug . '">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

        parent::render_sections(true);

        echo '</div>';


        echo '</div>';
    }
}
