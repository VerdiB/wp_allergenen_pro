<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Allergens_Dietary_Ictoria_Dashboard_Page {

    private static $instance = null;

    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register settings for this page
        add_action('admin_init', [$this, 'settings_init']);
    }

    public function settings_init() {
        // Register a new setting for this page
        register_setting('allergens_dietary_ictoria', 'allergens_dietary_ictoria_dashboard_options');

        // Load sections for this page
        Allergens_Dietary_Ictoria_Dashboard_Section_Main::get_instance();
        // Add more sections here as needed
    }

    public function render_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_GET['settings-updated'])) {
            add_settings_error('allergens_dietary_ictoria_messages', 'allergens_dietary_ictoria_message', __('Settings Saved', 'allergens-dietary-ictoria'), 'updated');
        }

        settings_errors('allergens_dietary_ictoria_messages');
        ?>
        <div id="ictoria-dashboard">
            <div class="ictoria-dashboard-container">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('allergens_dietary_ictoria');
                    do_settings_sections('allergens_dietary_ictoria');
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}
