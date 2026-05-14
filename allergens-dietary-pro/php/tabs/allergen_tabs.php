<?php

if (!defined('ABSPATH')) {
    exit;
}

if (! class_exists('Allergens_Dietary_Tabs') ) {
    require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/tabs/allergen_tabs.php';
}

/**
 * @class Allergens_Dietary_Pro_License_Tabs
 * @brief Class that creates the tabs after you filled in the licence key
 * the user can click on the tabs to edit their allergens
 * @author Verdi-B
 * @date 12-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Pro_Tabs extends Allergens_Dietary_Tabs
{    
    // public function __construct()
    public function __construct()
	{
        parent::__construct();
        //load js
		wp_register_script('Allergens_Dietary_Pro_Show_Allergens', plugins_url(ALLERGENS_DIETARY_PRO_NAME . '/assets/js/script.js'), array('jquery'));
        wp_enqueue_script( 'Allergens_Dietary_Pro_Show_Allergens');

        //load css
        wp_register_style('allergens-dietary-css-pro', plugins_url(ALLERGENS_DIETARY_PRO_NAME . '/assets/css/allergens-dietary-pro.css'));
	    wp_enqueue_style('allergens-dietary-css-pro');
	}

    public function showtabs()
    {
        //flexbox voor tabs
        $html = '<div id="tabs_flexbox" class="nav-tab-wrapper">';
        $html .= '<a class="nav-tab" href="'.get_admin_url(null, 'admin.php?page=allergens-dietary-show-allergens').'">' . __("See allergens", "allergens-dietary-pro") . '</a>';
        $html .= '<a class="nav-tab" href="'.get_admin_url(null, 'admin.php?page=allergens-dietary-add-allergen').'">' . __("Create allergens", "allergens-dietary-pro") . '</a>';
        $html .= '<a class="nav-tab" href="'.get_admin_url(null, 'admin.php?page=allergens-dietary-update-allergen').'">' . __("Change allergens", "allergens-dietary-pro") . '</a>';
        $html .= '<a class="nav-tab" href="'.get_admin_url(null, 'admin.php?page=allergens-dietary-Info').'">' . __("Info", "allergens-dietary-pro") . '</a>';
        $html .= '</div>';
        $html .= '<section id="added"></section> <br> <br>';
        echo $html;
        //moet nog aangepast worden in css
    }
    public function showpages()
    {
        //hier moet bijvoorbeeld een functie komen die de inhoud van de pagina verandert.
    }

    public static function getStyles()
    {
        wp_register_style('allergens-dietary-pro-css', plugins_url(ALLERGENS_DIETARY_PRO_NAME . '/assets/css/allergens-dietary-pro.css'));
        wp_enqueue_style('allergens-dietary-pro-admin-css', plugins_url('assets/css/allergens-dietary-pro.css', ALLERGENS_DIETARY_PRO_FILE));
    }
}

/*$myInstance = new Allergens_Dietary_Pro_Tabs;
$myInstance->js_add_help_tab();

add_action('added', 'js_add_help_tab', 50);*/