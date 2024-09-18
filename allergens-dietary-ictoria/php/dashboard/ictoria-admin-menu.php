<?php
if (!defined('ABSPATH')) {
    exit;
}

define('IAM_DIR', __DIR__);

class Ictoria_Admin_Menu
{
    private static $_instance = null;
    private static $directories = [
        IAM_DIR . '/utilities/',
        IAM_DIR . '/utilities/database/',
        IAM_DIR . '/utilities/base_classes/',
        IAM_DIR . '/page_ictoria-dashboard/',
        IAM_DIR . '/page_allergens-dietary/',
        IAM_DIR . '/page_settings/',
    ];
    private static $autoload_styles = [];
    private static $autoload_scripts = [];
    private static $autoload_pages = [];

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

        IAM_Page_Ictoria_Dashboard::instance();
        IAM_Page_Allergens_Dietary::instance();
        IAM_Page_Settings::instance();
    }

    public static function iam_style()
    {
        wp_enqueue_style(
            'variables-css',
            plugins_url('assets/css/variables.css', IAM_DIR)
        );

        wp_enqueue_style(
            'ictoria-admin-menu-css',
            plugins_url('dashboard/ictoria-admin-menu.css', IAM_DIR)
        );

        foreach (self::$autoload_styles as $handle => $style) {
            wp_enqueue_style($handle, plugins_url($style[0], IAM_DIR), $style[1], $style[2]);
        }
    }

    public static function iam_script()
    {
        wp_enqueue_script(
            'iam-js',
            plugins_url('dashboard/ictoria-admin-menu.js', IAM_DIR),
            ['jquery'],
            false,
            true
        );

        foreach (self::$autoload_scripts as $handle => $script) {
            wp_enqueue_script($handle, plugins_url($script[0], IAM_DIR), $script[1], $script[2], $script[3]);
        }
    }

    public static function autoload($class_name)
    {
        $name = str_replace('_', '-', strtolower($class_name));
        $file_name = $name . '.php';
        $file_name_css = $name . '.css';
        $file_name_js = $name . '.js';

        if (strpos($class_name, 'IAM_') === 0) {
            foreach (self::$directories as $directory) {
                if (file_exists($directory . $file_name)) {
                    require_once $directory . $file_name;

                    if (strpos(basename($directory), 'page_') === 0) {
                        if (file_exists($directory . $file_name_css)) {
                            self::$autoload_styles[$name . '-css'] = [
                                'dashboard/page_' . str_replace('iam-page-', '', $name) . '/' . $file_name_css,
                                [],
                                'all',
                            ];
                        }

                        if (file_exists($directory . $file_name_js)) {
                            self::$autoload_scripts[$name . '-js'] = [
                                'dashboard/page_' . str_replace('iam-page-', '', $name) . '/' . $file_name_js,
                                ['jquery'],
                                false,
                                true,
                            ];
                        }
                    }

                    return;
                }
            }
        }
    }
}

spl_autoload_register(['Ictoria_Admin_Menu', 'autoload']);

Ictoria_Admin_Menu::instance();
