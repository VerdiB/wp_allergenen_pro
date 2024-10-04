<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' )){
    require_once( ABSPATH . '/wp-admin/includes/class-wp-list-table.php' );
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
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

        public function __construct() {
            parent::__construct([
                'singular' => 'item', 
                'plural'   => 'items', 
                'ajax'     => false,
            ]);
        }
    
        public function get_table_columns_and_data() {
            
            global $wpdb;
        
            $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

            $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name", ARRAY_A);

            $column_names = [];
        
            foreach ($columns as $column) {
                $column_names[$column["Field"]] = ucfirst(str_replace('_', ' ', $column['Field']));
            }

            $data = $wpdb->get_results("SELECT allergy_name FROM $table_name", ARRAY_A);

            $allergy_names = [];

            foreach ($data as $row) {
                $allergy_names[] = $row['allergy_name'];
            }

            return [
                'columns' => $column_names,
                'data' => $allergy_names
            ];
            
        }

        public function column_default( $item, $column_name ) {
            $translationOfAllergenNumbers = "Diet";
            $translationOfIsActiveNumbers = "Inactive";

            if ($item[ 'is_allergy' ] == 1){
                $translationOfAllergenNumbers = "Allergy";
            }

            if ($item[ 'is_active' ] == 1){
                $translationOfIsActiveNumbers = "Active";
            }

            switch ( $column_name ) {
                case 'is_active':
                    return $translationOfIsActiveNumbers;
                    case 'allergy_name':
                        return esc_html( $item[ $column_name ] );
                    case 'allergy_description':
                        return esc_html( $item[ $column_name ] );
                    case 'is_allergy':
                        return $translationOfAllergenNumbers;
                default:
                    return print_r( $item, true ); // Fallback voor onbekende kolommen
            }
    }

        public function column_cb($item){
            return sprintf('<input type="checkbox" name="post[]" value="%s"/>', $item['allergy_name']);
        }

        public function single_row( $item ) {
            echo '<tr>';
            $this->single_row_columns( $item );
            echo '</tr>';
        }

        public function get_bulk_actions(){
            $actions = array();

            $actions['on/off'] ='<a href="#">'.__( 'on/off' ).'</a>';

            return $actions;
        }

        protected function single_row_columns( $item ) {
            list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();
    
            foreach ( $columns as $column_name => $column_display_name ) {
                $classes = "$column_name column-$column_name";
                if ( $primary === $column_name ) {
                    $classes .= ' has-row-actions column-primary';
                }
    
                if ( in_array( $column_name, $hidden, true ) ) {
                    $classes .= ' hidden';
                }

                $data = 'data-colname="' . esc_attr( wp_strip_all_tags( $column_display_name ) ) . '"';
    
                $attributes = "class='$classes' $data";
    
                if ( 'cb' === $column_name ) {
                    echo '<th scope="row" class="check-column">';
                    echo $this->column_cb( $item );
                    echo '</th>';
                } elseif ( method_exists( $this, '_column_' . $column_name ) ) {
                    echo call_user_func(
                        array( $this, '_column_' . $column_name ),
                        $item,
                        $classes,
                        $data,
                        $primary
                    );
                } elseif ( method_exists( $this, 'column_' . $column_name ) ) {
                    echo "<td $attributes>";
                    echo call_user_func( array( $this, 'column_' . $column_name ), $item );
                    echo $this->handle_row_actions( $item, $column_name, $primary );
                    echo '</td>';
                } else {
                    echo "<td $attributes>";
                    echo $this->column_default( $item, $column_name );
                    echo $this->handle_row_actions( $item, $column_name, $primary );
                    echo '</td>';
                }
            }
        }
    
        public function get_columns() {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'is_active' => 'Active or Inactive',
                'allergy_name' => 'Allergy name',
                'allergy_description' => 'Allergy description',
                'is_allergy' => 'Allergy or Dietary'
            );
            return $columns;
        }
    
        public function prepare_items() {
            global $wpdb;

            $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
            $result = $this->get_table_columns_and_data();
    
            $this->_column_headers = [$result['columns'], [], []];

            $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A); // Alle gegevens ophalen
    
            $this->_column_headers = [$this->get_columns(), [], []];
    
            $this->items = $data;
        }

        public function process_bulk_action($data) {  
            global $wpdb;
            
            // If the delete bulk action is triggered
            if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'on/off' )
            || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'on/off' )
            ) {
                if ($data[0] == "on/off"){
                    foreach ($data[1] as $key => $value){
                        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
                        $sql = $wpdb->get_results(
                            "SELECT * FROM $table_name WHERE is_active = '$value'"
                        );

                        if ($sql > 0){
                            Allergens_Dietary_Ictoria_Allergen_Queries::activationUpdate($data[1]);
                            break;
                        }
                    }
                }
            }else{
                error_log("It doesn't work.");
            }
            }

        public static function getInstance() {
            if ( self::$_instance === null ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function table_page() {
            $table = new Allergens_Dietary_Ictoria_Show_Allergens();
            $table->prepare_items();
            $self = htmlspecialchars($_SERVER["PHP_SELF"]);
            echo '<form action="#" method="POST"';
            echo "<table class='wp-list-table widefat fixed striped table-view-list pages'>";
                $table->display();
            echo "</table>";
            echo "</form>";
        }
    
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = new Allergens_Dietary_Ictoria_Show_Allergens();
    if (isset( $_POST['action'] ) && $_POST['action'] == 'on/off'){

        $counter = 0;
        $process_data = [];
    
        foreach ($_POST as $key => $value){
            if ($counter > 1){
                $process_data[] = $value;
            }
            $counter++;
        }

        $table->process_bulk_action($process_data);
    }
}

