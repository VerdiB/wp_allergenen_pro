<?php

if (! defined('ABSPATH')) {
    exit;
}

if(!class_exists('Allergens_Dietary_Allergy_Product_Queries')){
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/DB/allergy_product.php';
}

/**
 * @class Allergens_Dietary_Pro_Allergy_Product_Queries
 * @brief This class is a singleton that handles all the queries for the allergens and dietary restrictions DB table.
 * @author Ictoria
 * @date 4-10-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Pro_Allergy_Product_Queries extends Allergens_Dietary_Allergy_Product_Queries
{
    protected function __construct() {}
}
