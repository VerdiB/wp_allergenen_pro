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
        $results = $wpdb->get_results( "SELECT * FROM $table_name" );

        //allergens

        $html = '<table id="tabs_flexbox" class="nav-tab-wrapper">
        <th>De allergenen: </th>';

        foreach ($results as $row){
        $html .= '<tr scope="row"">
            <td>' . esc_html( $row->allergy_name ) . ':</td>
            <td>' . esc_html( $row->allergy_description ) . '</td>
            </tr>';
        }

        $html .= '</table>';
        echo $html;

        //Dietary

        $html = '<table id="tabs_flexbox" class="nav-tab-wrapper">
        <th>De dieten: </th>';

        foreach ($results as $row){
            if ($row->category == "dietary"){
        $html .= '<tr scope="row">
            <td>' . esc_html( $row->allergy_name ) . ':</td>
            <td>' . esc_html( $row->allergy_description ) . '</td>
            </tr>';
        }
    }

        $html .= '</table>';
        echo $html;
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