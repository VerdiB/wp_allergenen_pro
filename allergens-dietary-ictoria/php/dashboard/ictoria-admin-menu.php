<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('IAM_DIR', __DIR__);

class Ictoria_Admin_Menu
{
    private static $_instance = null;
    // page directories
    private static $directories = [
        IAM_DIR . '/' . 'utilities/',
        IAM_DIR . '/' . 'utilities/base_classes/',
        IAM_DIR . '/' . 'page_ictoria-dashboard/',
        IAM_DIR . '/' . 'page_allergens-dietary/',
        IAM_DIR . '/' . 'page_settings/',
    ];

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        add_action('rest_api_init', ['IAM_Rest_Routes', 'register_iam_rest_routes']);

        add_action('admin_enqueue_scripts', [__CLASS__, 'iam_style']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'iam_script']);

        // IAM_Database_Connect::instance();
        // IAM_Rest_Routes::instance();

        // // top-level menu
        IAM_Page_Ictoria_Dashboard::instance();
        // // submenus
        IAM_Page_Allergens_Dietary::instance();
        IAM_Page_Settings::instance();
    }

    public static function iam_style()
    {
        wp_enqueue_style('iam-css', plugins_url('dashboard/assets/css/iam.css', IAM_DIR));
    }

    public static function iam_script()
    {
        wp_enqueue_script(
            'iam-js',
            plugins_url('dashboard/assets/js/iam.js', IAM_DIR),
            '',
            false,
            false
        );
    }

    public static function autoload($class_name)
    {
        /* The $class_name(e.g. IAM_Page_Ictoria_Dashboard) gets converted to $file_name(e.g. iam-page-ictoria-dashboard.php) */
        $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';
        if (strpos($class_name, 'IAM_') === 0) {
            /* Check if the file exists in any of the $directories */
            foreach (self::$directories as $directory) {
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
spl_autoload_register(['Ictoria_Admin_Menu', 'autoload']);

// Initialize the main dashboard class
Ictoria_Admin_Menu::instance();
