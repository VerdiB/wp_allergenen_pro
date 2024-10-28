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

    public function addAllergyProduct( int $product_id, string $allergen ) {
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

    public function getAllergyProduct( int $product_id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';

        $sql = $wpdb->prepare(
            "SELECT allergy_name
            FROM %i
            WHERE product_id = %d"
        , array($table_name, $product_id));

        return $wpdb->get_results( $sql, ARRAY_A );
    }

    public function deleteAllergyProduct(int $product_id, string $allergen){
        global $wpdb;
        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';
        $sql = $wpdb->prepare(
            "DELETE FROM %i
            WHERE product_id = %d AND allergy_name = %s",
            array($table_name, $product_id, $allergen));
        
        return $wpdb->query($sql);
    }
}