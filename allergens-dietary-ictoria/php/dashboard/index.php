<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Autoloader function for classes
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

    private static $instance = null;

    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register actions for admin menu
        add_action('admin_menu', [$this, 'options_page']);
    }

    // Add the top-level menu page
    public function options_page() {
        add_menu_page(
            'Ictoria Plugin Dashboard',
            'Ictoria Plugins',
            'manage_options',
            'allergens-dietary-ictoria',
            [$this, 'load_dashboard_page'],
            'dashicons-admin-settings',
            56
        );
    }

    // Load the dashboard page
    public function load_dashboard_page() {
        // Load the dashboard page controller
        $dashboard_page = Allergens_Dietary_Ictoria_Dashboard_Page::get_instance();
        $dashboard_page->render_page();
    }
}

// Instantiate the main dashboard class
Allergens_Dietary_Ictoria_Dashboard::get_instance();
