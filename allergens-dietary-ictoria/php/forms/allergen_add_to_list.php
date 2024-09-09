<?php

//exit if user can access this file directly
if(!defined('ABSPATH')){
    exit;
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Add_2_List' ) ) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_add_to_list.php';
}

class Allergens_Dietary_Ictoria_Add_2_List {
    private static ?self $instance = NULL;

 

}


$attributes_allergen = 
add_query_arg( array( 
    'key' => array(
        'category' => __('allergen','allergens-dietary-ictoria'),
        'title' => __('key', 'allergens-dietary-ictoria') , 
        'status' => __('active', 'allergens-dietary-ictoria'),
        'filter-action' => __('exclude', 'allegens-dietary-ictoria') ,
        'filter-extra' => $no,
        'icon' => __('url', 'allegens-dietary-ictoria') , // icon upload option in WP-dashboard
            ALLERGENS_DIETARY_ICTORIA_DIRNAME
    ) ));

// add allergen button in WordPress
submit_button( __('add allergen', 'allergens-dietary-ictoria'), 
'primary', //CSS class for the button
true,  // Wrap the button in a <p> tag
$attributes_allergen // Additional attributes like 'id'
);







?>