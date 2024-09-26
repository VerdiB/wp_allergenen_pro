<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @class Allergens_Dietary_Ictoria_License_Info
 * @brief Class that creates the info
 * the user can see the info
 * @author T.K.
 * @date 12-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Ictoria_Info {
    private static ?self $_instance = null;

    

    public function showInfo() {
        //flexbox voor tabs
        $html = '<div id="info_grid" class="nav-tab-wrapper">
        <div><h1 class="premium">PREMIUM  [Requires licence]</h1>
        <ol>
            <li class="contains">
                Updating allergies [PREMIUM]
            </li>
            <li class="contains">
                Changing allergy themes [PREMIUM]
            </li>
            <li class="contains">
                Deleting allergies [PREMIUM]
            </li>
             <li class="contains">
                Adding allergies [PREMIUM]
            </li>
            <li class="contains">
                Custom look on product [PREMIUM]
            </li>
            <li class="contains">
                Custom look in store [PREMIUM]
            </li>
        </ol>
        </div>
        <div><h1 class="free">FREE VERSION  [Standard]</h1>
        <ol>
            <li class="contains">
                Connecting allergies to products [FREE]
            </li>
            <li class="contains">
                Wordpress theme fiendly styles [FREE]
            </li>
            <li class="contains">
                An allergen overview [FREE]
            </li>
            <li class="contains">
                Turning the the use of allergies on/off [FREE]
            </li>
            </ol>
        </div>
        </div>
        <br> <br>';
        echo $html;
        //moet nog aangepast worden in css
    }

    public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    public static function getStyles() {
        wp_register_style('allergens-dietary-ictoria-css', plugins_url(ALLERGENS_DIETARY_ICTORIA_NAME.'/assets/css/allergens-dietary-ictoria.css'));
        wp_enqueue_style('allergens-dietary-ictoria-admin-css', plugins_url( 'assets/css/allergens-dietary-ictoria.css', ALLERGENS_DIETARY_ICTORIA_FILE ));
    }
}