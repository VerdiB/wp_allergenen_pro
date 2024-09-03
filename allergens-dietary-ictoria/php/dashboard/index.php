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
        // Register actions for the admin menu
        // add_action('admin_menu', [$this, 'initialize_components']);
        add_action('admin_menu', [ADI_Dashboard_Main_Page::instance(), 'add_menu_page']);
        add_action('admin_init', [ADI_Dashboard_Main_Page::instance(), 'admin_init_hooks']);
        // add_action did both admin_menu & admin_init inside the wrapping add_action -> admin_menu, might have been the problem
    }

    public function initialize_components()
    {
        // ADI_Dashboard_Main_Page::instance();
        ADI_Dashboard_Main_Page::instance();
    }

    public static function autoload($class_name)
    {
        // Base directory for the namespace prefix
        // Array of directories to search for classes
        $directories = [
            ADI_DASHBOARD_DIR . '/' . 'sections/',
            ADI_DASHBOARD_DIR . '/' . 'pages/',
        ];

        $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';
        if (strpos($class_name, 'ADI_') === 0) {
            foreach ($directories as $directory) {
                $file = $directory . $file_name;
                // print_r($file . '<br>');
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
