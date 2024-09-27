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

    public function get_columns() {
        $columns = array(
            'cb'      => '<input type="checkbox" />', // Checkbox column for bulk actions
            'name'    => 'Name',
            'email'   => 'Email',
            'role'    => 'Role'
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

        $sql = $wpdb->prepare(
            "SELECT * FROM $table_name"
        );
        $results = $wpdb->get_results( $sql , ARRAY_A );
   
        $html = '<table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead> 
        <th scope="col" id="name" class="manage-column column-name column-primary"> <input id="cb-select-all-1" name="lijst" type="checkbox">  Allergenen en dieten: <td>
        <label for="lijst">
            <span class="screen-reader-text">Alles selecteren</span>
        </label>
        Beschrijving:
        </td></th>
        <th scope="col" id="name" class="manage-column column-name column-primary"> </th>
        <th scope="col" id="name" class="manage-column column-name column-primary"> Allergeen of dieet: </th></thead>';

        try {
            if(!empty($results)){
            foreach ($results as $row){
                $allergenOrDieet = "";
                if ($row['is_allergy'] == 1){
                    $allergenOrDieet = "Allergeen";
                }else{
                    $allergenOrDieet = "Dieet";
                }
                $html .= '<tr scope="row" id="post-1" class="iedit author-self level-0 post-8 type-page status-publish hentry">
                <td class="allergen-dietary_item label"><input id="cb-select-all-1" name="lijst" type="checkbox">' . esc_html( $row['allergy_name'] ) . ':</td>
                <td class="title column-title has-row-actions column-primary page-title">' . esc_html( $row['allergy_description'] ) . '</td>
                <td class="title column-title has-row-actions column-primary page-title"></td>
                <td class="allergen-dietary_item label">' . $allergenOrDieet . '</td>
                </tr>';
            }
            }else{
                _e("is empty");
            }
        } catch (Exception $e) {
            echo "<script>console.log(Error: " . $e->getMessage() . ") </script>";
            print_r($e->getMessage());
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

    //Bulk/quick actions zijn voor volgende sprint.
}