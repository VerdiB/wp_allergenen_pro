
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
            'My Page Title',
            'My Page',
            'manage_options',
            'my-menu-page-slug',
            array(
                $this,
                'myAdminPage'
            )
        );
    }

    public function myAdminPage() {
        // echo the HTML here ......
    }
}

// call the class and add the menus automatically
// $MyPluginAddMenu = MyPluginAddMenu::instance();



?>
