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

    public function getAllergyProduct( int $product_id, string $allergen  = null) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';
        $sql = '';
        if(is_null($allergen)){
            $sql = $wpdb->prepare(
                "SELECT ap.allergy_name
                FROM %i as ap
                JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as a on ap.allergy_name = a.allergy_name
                WHERE ap.product_id = %d and a.is_active = 1" 
            , array($table_name, $product_id));
        } else {
            $sql = $wpdb->prepare(
                "SELECT ap.allergy_name
                FROM %i as ap
                JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as a on ap.allergy_name = a.allergy_name
                WHERE ap.product_id = %d and a.is_active = 1 and ap.allergy_name = %s" 
            , array($table_name, $product_id, $allergen));
        }


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



    public function getFilteredProducts( ?array $allergens, ?array $diaetary ) {
        $allergens = (is_null($allergens) || empty($allergens)) ? "" : $allergens;
        $diaetary = (is_null($diaetary) || empty($diaetary)) ? "" : $diaetary;
        global $wpdb;

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';
        $sql = $wpdb->prepare(
            "SELECT DISTINCT ap.product_id
            FROM %i AS ap
            JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy AS a ON ap.allergy_name = a.allergy_name
            WHERE ap.product_id NOT IN (
                SELECT ap_sub.product_id
                FROM {$wpdb->prefix}allergens_dietary_ictoria_allergy_product AS ap_sub
                JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy AS a_sub ON ap_sub.allergy_name = a_sub.allergy_name
                WHERE a_sub.is_allergy = 1
                AND a_sub.allergy_name IN ('". (is_array($allergens)?implode("',''",$allergens): $allergens) ."')
            )
            AND a.is_allergy = 0
            AND a.allergy_name IN ('" .(is_array($diaetary)?implode("',''",$diaetary): $diaetary)."')",
            $table_name
        );

        return $wpdb->get_results( $sql, ARRAY_A);
    }
}