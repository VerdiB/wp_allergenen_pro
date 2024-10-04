<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @class Allergens_Dietary_Ictoria_Allergy_Product_Queries
 * @brief This class is a singleton that handles all the queries for the allergens and dietary restrictions DB table.
 * @author V.B.
 * @date 4-10-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Ictoria_Allergy_Product_Queries {
    private static ?self $_instance = null;

    public static function getInstance() {
        if ( self::$_instance === null ) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        
    }

    public function addAllergyProduct( string $allergen, int $product_id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';

        $wpdb->insert(
            $table_name,
            array(
                'product_id'    => $product_id,
                'allergy_name' => $allergen,
            )
        );

        return ( isset( $wpdb->insert_id ) ) ? true : false;
    }
}