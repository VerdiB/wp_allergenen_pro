<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Page
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        // Register settings for this page
        add_action('admin_init', [$this, 'admin_init_hooks']);
    }

    public function admin_init_hooks()
    {
        $this->main_page_init();
        // Initialize the main section (this should ideally happen elsewhere, like in a section-specific class)
        ADI_Dashboard_Main_Section::instance()->main_section_init();
    }

    public function main_page_init()
    {
        // Register a new setting for this page
        register_setting('allergens_dietary_ictoria', 'adi_settings', [$this, 'validate_settings']);
        // Add more initialization tasks specific to this page here if needed
    }

    public function render_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        settings_errors('allergens_dietary_ictoria_messages');
        echo $this->get_dashboard_html();
    }

    private function get_dashboard_html()
    {
        $title = esc_html('Plugin Dashboard', 'allergens_dietary_ictoria');
        // $title = esc_html(get_admin_page_title());
        // Using HEREDOC syntax for better readability
        $html = <<<HTML
        <div class="wrap">
            <div id="adi-dashboard">
                <div class="adi-dashboard-container">
                    <img src="https://ictoria.nl/wp-content/uploads/2023/07/ICTORIA-2.0-LOGO_Tekengebied-1.png.webp" class="adi-dashboard-logo" alt="ICTORIA logo">
                    <h1 class="adi-dashboard-h1">$title</h1>
                    <form class="adi-dashboard-form" action="options.php" method="post">
                        {$this->get_settings_fields_html()}
                        {$this->get_submit_button_html()}
                    </form>
                </div>
            </div>
        </div>
        HTML;

        return $html;
    }

    private function get_settings_fields_html()
    {
        // Capture output buffering to handle settings_fields output
        ob_start();
        settings_fields('allergens_dietary_ictoria');
        do_settings_sections('allergens_dietary_ictoria');
        return ob_get_clean();
    }

    private function get_submit_button_html()
    {
        // Capture output buffering to handle submit_button output
        ob_start();
        submit_button('Save Settings');
        return ob_get_clean();
    }

    // Placeholder for validate_settings if needed
    public function validate_settings($input)
    {
        // Add your validation logic here
        return $input;
    }
}
