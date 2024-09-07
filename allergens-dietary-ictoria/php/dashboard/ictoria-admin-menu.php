<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('IAM_DIR', __DIR__);

class Ictoria_Admin_Menu
{
    private static $_instance = null;
    private static $directories = [
        IAM_DIR . '/' . 'utilities/base_classes/',
        IAM_DIR . '/' . 'menu_ictoria-dashboard/',
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
        add_action('admin_enqueue_scripts', [__CLASS__, 'iam_style']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'iam_script']);

        IAM_Menu_Ictoria_Dashboard::instance();
    }

    public static function iam_style()
    {
        wp_enqueue_style('iam-css', plugins_url('assets/css/iam.css', IAM_DIR));
    }

    public static function iam_script()
    {
        wp_enqueue_script(
            'iam-js',
            plugins_url('assets/js/iam.js', IAM_DIR),
            '',
            false,
            false
        );
    }

    public static function autoload($class_name)
    {
        $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';
        if (strpos($class_name, 'IAM_') === 0) {
            foreach (self::$directories as $directory) {
                $file = $directory . $file_name;

                echo $file . '<br>';

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
