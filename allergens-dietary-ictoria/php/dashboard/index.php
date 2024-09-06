<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('ADI_DASHBOARD_DIR', __DIR__);

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
        add_action('admin_enqueue_scripts', [__CLASS__, 'adi_dashboard_style']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'adi_dashboard_script']);
        // Other things can be initialized here if needed
    }

    public static function adi_dashboard_style()
    {
        wp_enqueue_style('adi-dashboard-css', plugins_url('assets/css/adi-dashboard.css', ALLERGENS_DIETARY_ICTORIA_FILE));
    }

    public static function adi_dashboard_script()
    {
        wp_enqueue_script(
            'adi-dashboard-js',
            plugins_url('assets/js/adi-dashboard.js', ALLERGENS_DIETARY_ICTORIA_FILE),
            ['jquery'],
            false,
            true
        );
    }

    public static function autoload($class_name)
    {
        // Array of directories to search for classes
        $directories = [
            ADI_DASHBOARD_DIR . '/' . 'base/',
            ADI_DASHBOARD_DIR . '/' . 'pages/',
            ADI_DASHBOARD_DIR . '/' . 'sections/',
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

// Autoload classes
spl_autoload_register(['Allergens_Dietary_Ictoria_Dashboard', 'autoload']);

// Initialize the main dashboard class
Allergens_Dietary_Ictoria_Dashboard::instance();

// Initialize the dashboard pages (might have to look into how to do this dynamically later)
ADI_Dashboard_Main_Page::instance();
ADI_Dashboard_Settings_Page::instance();
