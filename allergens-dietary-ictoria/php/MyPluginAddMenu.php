<?php
//exit if user can access this file directly
if(!defined('ABSPATH')){
    exit;
}

class MyPluginAddMenu {

    private static $instance = NULL;

    /***
     * Main instance
     * 
     * @staticvar   array   $instance
     * @return      The one true instance
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new MyPluginAddMenu();
        }
        return self::$instance;
    }

    private function __construct(){
        add_action('admin_menu', array(
            $this,
            'addMyAdminMenu'
        ));
        // return self::addMyAdminMenu();
    }

    public function addMyAdminMenu() {
        
        add_menu_page(
            __('Allergens and Dietary', 'allergens-dietary-ictoria'),
            'Ictoria',
            'manage_options',
            'allergens-dietary-options',
            array(
                $this,
                'myAdminPage'
            )
        );

        add_submenu_page(
            'my-menu-page-slug',
            __('License key', 'allergens-dietary-ictoria'),
            __('License key', 'allergens-dietary-ictoria'),
            'manage_options',
            'allergens-dietary-license',
            array(
                $this,
                'licenseform'
            )
        );

        add_submenu_page(
            'my-menu-page-slug',
            __('allergens-dietary-ictoria'),
            __('allergens-dietary-ictoria'),
            'manage-options',
            'allergens-dietary-add-allergens',
            array(
                $this,
                'add-allergens'
            )
        );
    }

    public function myAdminPage() {
        // echo the HTML here ......
    }

    public function licenseForm() {
        if ( ! class_exists( 'Allergens_Dietary_Ictoria_Form' ) ) {
            require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
        }
        Allergens_Dietary_Ictoria_Form::setFormType( FormType::LICENSE );
        Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
    }
}

// call the class and add the menus automatically
// $MyPluginAddMenu = MyPluginAddMenu::instance();



?>
