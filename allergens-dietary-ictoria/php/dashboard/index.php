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
        // Other things can be initialized here if needed
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
