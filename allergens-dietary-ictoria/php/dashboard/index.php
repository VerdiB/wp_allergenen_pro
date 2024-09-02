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
        add_action('admin_menu', [$this, 'initialize_components']);
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

// define('ADI_DASHBOARD_DIR', __DIR__);

// // Autoload classes
// spl_autoload_register(['Allergens_Dietary_Ictoria_Dashboard', 'autoload']);

// Initialize the main dashboard class
// Allergens_Dietary_Ictoria_Dashboard::instance();

// class Allergens_Dietary_Ictoria_Dashboard
// {
//     private static $_instance = null;

//     public static function instance()
//     {
//         if (is_null(self::$_instance)) {
//             self::$_instance = new self();
//         }
//         return self::$_instance;
//     }

//     private function __construct()
//     {
//         // Register actions for the admin menu
//         add_action('admin_menu', [$this, 'dashboard_page']);
//         add_action('admin_enqueue_scripts', [__CLASS__, 'adi_dashboard_style']);
//         add_action('admin_enqueue_scripts', [__CLASS__, 'adi_dashboard_script']);

//         // Initialize all necessary components
//         $this->initialize_components();
//     }

//     public static function adi_dashboard_style()
//     {
//         wp_enqueue_style('adi-dashboard-css', plugins_url('assets/dashboard/css/adi-dashboard.css', ALLERGENS_DIETARY_ICTORIA_FILE));
//     }
//     public static function adi_dashboard_script()
//     {
//         wp_enqueue_script(
//             'adi-dashboard-js',
//             plugins_url('assets/dashboard/js/adi-dashboard.js', ALLERGENS_DIETARY_ICTORIA_FILE),
//             ['jquery'],
//             false,
//             true
//         );
//     }

//     // Initialize all dashboard components (pages, sections, etc.)
//     private function initialize_components()
//     {
//         // Initialize the main page and any other components
//         ADI_Dashboard_Main_Page::instance();
//         // You can add more initializations for other sections here if needed
//         $section = new ADI_Dashboard_Main_Section();
//         // ADI_Dashboard_Main_Section::instance();
//     }

//     public function dashboard_page()
//     {
//         add_menu_page(
//             'Ictoria Plugin Dashboard',
//             'Ictoria Plugins',
//             'manage_options',
//             'allergens-dietary-ictoria',
//             [$this, 'load_dashboard_page'],
//             'dashicons-admin-settings',
//             56
//         );
//     }

//     public function load_dashboard_page()
//     {
//         $dashboard_page = ADI_Dashboard_Main_Page::instance();
//         $dashboard_page->render_page();
//     }

//     public static function autoload($class_name)
//     {
//         // Base directory for the namespace prefix
//         $base_dir = ADI_DASHBOARD_DIR . '/';

//         // Array of directories to search for classes
//         $directories = [
//             $base_dir . 'sections/',
//             $base_dir . 'pages/',
//         ];

//         $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';

//         if (strpos($class_name, 'ADI_') === 0) {
//             foreach ($directories as $directory) {
//                 $file = $directory . $file_name;
//                 if (file_exists($file)) {
//                     require_once $file;
//                     return;
//                 }
//             }
//         }
//     }
// }
