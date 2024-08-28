<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('ADI_DASHBOARD_DIR', __DIR__);

// Autoload classes
spl_autoload_register(['Allergens_Dietary_Ictoria_Dashboard', 'autoload']);

// Initialize the main dashboard class
Allergens_Dietary_Ictoria_Dashboard::instance();

class Allergens_Dietary_Ictoria_Dashboard
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
        // Register actions for the admin menu
        add_action('admin_menu', [$this, 'dashboard_page']);
        // Initialize all necessary components
        $this->initialize_components();
    }

    // Initialize all dashboard components (pages, sections, etc.)
    private function initialize_components()
    {
        // Initialize the main page and any other components
        ADI_Dashboard_Main_Page::instance();
        // You can add more initializations for other sections here if needed
        ADI_Dashboard_Main_Section::instance();
    }

    public function dashboard_page()
    {
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

    public function load_dashboard_page()
    {
        $dashboard_page = ADI_Dashboard_Main_Page::instance();
        $dashboard_page->render_page();
    }

    public static function autoload($class_name)
    {
        // Base directory for the namespace prefix
        $base_dir = ADI_DASHBOARD_DIR . '/';

        // Array of directories to search for classes
        $directories = [
            $base_dir . 'sections/',
            $base_dir . 'pages/',
        ];

        $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';

        if (strpos($class_name, 'ADI_') === 0) {
            foreach ($directories as $directory) {
                $file = $directory . $file_name;
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    }
}
