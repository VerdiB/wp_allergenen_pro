<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoloader function for classes.
 *
 * @param string $class_name The name of the class to load.
 */
spl_autoload_register(function ($class_name) {
    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // Array of directories to search for classes
    $directories = [
        $base_dir . 'sections/',
        $base_dir . 'pages/'
    ];

    // Replace namespace separators with directory separators in the class name and append with .php
    $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';

    // Iterate over each directory to find the class file
    foreach ($directories as $directory) {
        $file = $directory . $file_name;
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

class Allergens_Dietary_Ictoria_Dashboard {

    // Singleton instance
    private static $instance = null;
    // Singleton instance method
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor
    private function __construct() {
        // Register actions
        add_action( 'admin_init', [ $this, 'settings_init' ] );
        add_action( 'admin_menu', [ $this, 'options_page' ] );
    }

    // Initialize settings
    public function settings_init() {
        // Register a new setting for "Allergens_Dietary_Ictoria" page.
        register_setting( 'Allergens_Dietary_Ictoria', 'allergens_dietary_ictoria_dashboard_options' );

        // Register upsell section
        /* add_settings_section(
            'Allergens_Dietary_Ictoria_section_upsell',
            __( 'More plugins by Ictoria.nl', 'Allergens_Dietary_Ictoria' ),
            [ $this, 'section_upsell_callback' ],
            'Allergens_Dietary_Ictoria'
        ); */
    }


    // Upsell section callback function
    /* public function section_upsell_callback( $args ) {
        ?>
        <div id="<?php echo esc_attr( $args['id'] ); ?>" class="ictoria-dashboard-section">
            <div class="ictoria-dashboard-section-col">
                <span>This is a plugin/product title</span>
                <span>&euro;50</span>

            </div>
            <div class="ictoria-dashboard-section-col">
                This is a review section
            </div>
        </div>
        <?php
    } */

    // Add the top level menu page
    public function options_page() {
        add_menu_page(
            'Ictoria Plugin Dashboard',
            'Ictoria.nl',
            'manage_options',
            'Allergens_Dietary_Ictoria',
            [ $this, 'options_page_html' ],
            'dashicons-admin-settings',
            56
        );
    }

    // Top level menu callback function
    public function options_page_html() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Add error/update messages
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'Allergens_Dietary_Ictoria_messages', 'Allergens_Dietary_Ictoria_message', __( 'Settings Saved', 'Allergens_Dietary_Ictoria' ), 'updated' );
        }

        // Show error/update messages
        settings_errors( 'Allergens_Dietary_Ictoria_messages' );
        ?>
        <div id="ictoria-dashboard">
            <div class="ictoria-dashboard-container">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <form action="options.php" method="post">
                    <?php
                    // Output security fields for the registered setting "Allergens_Dietary_Ictoria"
                    settings_fields( 'Allergens_Dietary_Ictoria' );
                    // Output setting sections and their fields
                    do_settings_sections( 'Allergens_Dietary_Ictoria' );
                    // Output save settings button
                    submit_button( 'Save Settings' );
                    ?>
                </form>
            </div>
        </div>
        <?php
    }
}

// Instantiate the class
Allergens_Dietary_Ictoria_Dashboard::get_instance();

Allergens_Dietary_Ictoria_Dashboard_Section_Main::get_instance();