<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' )){
    require_once( ABSPATH . '/wp-admin/includes/class-wp-list-table.php' );
}

/**
 * @class Allergens_Dietary_Ictoria_Show_Allergens
 * @brief Class that shows the allergens
 * the user can see the already created allergies
 * @author T.K.
 * @date 24-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Ictoria_Show_Allergens extends WP_List_Table {

    private static ?self $_instance = null;

    function get_columns() {
        $columns = array(
            'cb'      => '<input type="checkbox" />', // Checkbox kolom
            'name'    => 'Naam',
            'email'   => 'E-mail',
            'phone'   => 'Telefoonnummer',
        );
        return $columns;
    }
    
    public function show_Allergens_form() {
        global $wpdb;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();

        $this->_column_headers = array( $columns, $hidden, $sortable );
        
        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $table_name2 = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
        $table_name3 = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

        $results = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $table_name"));
       /* $results = $wpdb->get_results( $wpdb->prepare(
            "SELECT 
                t1.allergy_name, 
                t1.allergy_description, 
                t2.attachment_name, 
                t3.attachment_path
            FROM 
                {$table_name} AS t1
            INNER JOIN 
                {$table_name2} AS t2 ON t1.allergy_name = t2.allergy_name
            INNER JOIN 
                {$table_name3} AS t3 ON t2.attachment_name = t3.attachment_name"
        ) ); */

        //allergens

        $html = '<table id="showallergens_flexbox" class="nav-tab-wrapper">
        <th>De allergenen: </th>';

        try {
            print_r("hello2");
            if(!empty($results)){
            foreach ($results as $row){
                print_r("hello3");
                var_dump($row);
                if ($row->is_allergy == 1){
                    print_r("hello4");
                $html .= '<tr scope="row">
                <td class="allergen-dietary_item label">' . esc_html( $row->allergy_name ) . ':</td>
                <td class="allergen-dietary_item">' . esc_html( $row->allergy_description ) . '</td>
                </tr>';
                }
            }
            }else{
                print_r("is empty");
            }
        } catch (Exception $e) {
            echo "<script>console.log(Error: " . $e->getMessage() . ") </script>";
            print_r($e->getMessage());
        }

        

        $html .= '</table>';
        echo $html;

        print_r("hello");

        //Dietary

        $html2 = '<table id="showallergens_flexbox" class="nav-tab-wrapper">
        <tr><th>De dieten: </th></tr>';

        
        try {
        foreach ($results as $row){
            if ($row->is_allergy == 0){
            $html2 .= '<tr scope="row">
            <td class="allergen-dietary_item label">' . esc_html( $row->allergy_name ) . ':</td>
            <td class="allergen-dietary_item label">' . esc_html( $row->allergy_description ) . '</td>
            <td class="allergen-dietary_item label"><img>' . esc_html( $row->attachment_path ) . '</img></td>
            </tr>';
            }
        }
    }catch (Exception $e){
        echo "Error: " . $e->getMessage(); 
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