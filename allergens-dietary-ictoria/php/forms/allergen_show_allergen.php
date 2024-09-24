<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @class Allergens_Dietary_Ictoria_Show_Allergens
 * @brief Class that shows the allergens
 * the user can see the already created allergies
 * @author T.K.
 * @date 24-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Ictoria_Show_Allergens {

    private static ?self $_instance = null;

    public function show_Allergens_form() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $table_name2 = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
        $results = $wpdb->get_results( "SELECT * FROM $table_name INNER JOIN $table_name2 ON allergy_name.attachment_name" INNER JOIN $table_name2 ON);
        $table_name2 = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

        $results2 = $wpdb->get_results( $wpdb->prepare( "
        SELECT 
            o.order_id, 
            c.customer_name, 
            p.product_name
         FROM 
            {$wpdb->prefix}orders AS o
        JOIN 
            {$wpdb->prefix}customers AS c ON o.customer_id = c.customer_id
        JOIN 
            {$wpdb->prefix}products AS p ON o.product_id = p.product_id
        WHERE 
            o.order_id = %d", 
));

        //allergens

        $html = '<table id="showallergens_flexbox" class="nav-tab-wrapper">
        <th>De allergenen: </th>';

        foreach ($results as $row){
            if ($row->is_allergy == 1){
        $html .= '<tr scope="row">
            <td class="allergen-dietary_item label">' . esc_html( $row->allergy_name ) . ':</td>
            <td class="allergen-dietary_item">' . esc_html( $row->allergy_description ) . '</td>
            </tr>';
            }
        }

        $html .= '</table>';
        echo $html;

        //Dietary

        $html2 = '<table id="showallergens_flexbox" class="nav-tab-wrapper">
        <tr><th>De dieten: </th></tr>';

        foreach ($results as $row){
            if ($row->is_allergy == 0){
        $html2 .= '<tr scope="row">
            <td class="allergen-dietary_item label">' . esc_html( $row->allergy_name ) . ':</td>
            <td class="allergen-dietary_item label">' . esc_html( $row->allergy_description ) . '</td>
            <td class="allergen-dietary_item label"><img>' . esc_html( $row->attachment_path ) . '</img></td>
            </tr>';
        }
    }

        $html2 .= '</table>';
        echo $html2;
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