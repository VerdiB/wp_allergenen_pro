<?php
namespace Admin;
use Allergens_Dietary_Ictoria_Functions;
// use WooCommerce\Abstracts\WC_Integration;
// use Allergens_Dietary_Ictoria_Functions;
// use Allergens_Dietary_Ictoria_Wc_Integration_Settings;
class Allergens_Dietary_Ictoria_Wc_Integration_Startup {

    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init_integration' ) );
    }

    public function init_integration() {
        // Check if the WC_Integration class exists
        if ( class_exists( 'WC_Integration' ) ) {
            
            include_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/src/allergens-dietary-ictoria-wc-integration-settings.php';
            
            add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );
            // load the plugin admin js files
            Allergens_Dietary_Ictoria_Functions::load_admin_js();
        } else {
            // the integration class of WooCommerce was not found, show error message
            $level   = 'notice-error';
            $message = sprintf( __( '%1$sThe WooCommerce Integration class was not found. Please make sure WooCommerce is installed correctly%2$s', 'allergens-dietary-ictoria' ), '<p>', '</p>' );
            Allergens_Dietary_Ictoria_Functions::error_notice( $level, $message );
        }
    }

    public function add_integration( $integrations ) {
        
        // require_once $this->_path . 'class-allergen-wc-integration-settings.php';
        $integrations[] = '\Allergens_Dietary_Ictoria_Wc_Integration_Settings';
        return $integrations;
    }
}
?>