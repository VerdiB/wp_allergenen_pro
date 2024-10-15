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

    private static $_instance = [];

    private function __construct() {
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
                    return print_r( $item, true );
            }
    }

        public function column_cb($item){
            return sprintf('<input type="checkbox" name="post[]" value="%s"/>', $item['allergy_name']);
        }

        public function single_row( $item ) {
            $this->column_location_id($item);
            echo '<tr>';
            $this->single_row_columns( $item );
            echo '</tr>';
        }

        public function get_bulk_actions(){

            $actions = array();

            $actions['on/off'] = '<a href="#">'.__( 'Change status', 'allergens-dietary-ictoria' ).'</a>' . wp_nonce_field('allergens_bulk_change_status', 'bulk_change_status_nonce');

            return $actions;
        }

        public function column_show_id($item)
    {
        $title = '<strong>' . $item['allergy_name'] . '</strong>';
        $actions = array(
            'Change status' => $this->edit_row_action($item['allergy_name']),
        );

        return sprintf($title . $this->row_actions($actions));
    }

    protected function edit_row_action($id)
	{
		$html = '';
		$html .= '<a href="?panel=Edit&id='. $id .'" id="single-edit-button-' . $id . '" class="single-edit-button" >' . __('Edit', 'allergens-dietary-ictoria') . '</a>';
		return $html;
	}

        public function handle_row_actions($item, $column_name, $primary){

            if ($primary !== $column_name){
                return '';
            }

            $action = [];
            $action['edit'] = '<a>'. __( 'Change status', 'allergens-dietary-ictoria' ) .'<a>';

            return $this->row_actions( $action );

        }

        public function column_change_status($item, $column_name, $primary){

            if($primary !== $column_name){
                return "";
            }

            $change_status_nonce = wp_create_nonce('allergens_change_status');
            
            $actions = array(
                'change_status' => sprintf(
                    '<a name="allergens_quick_edit_location_address" value="%s" href="?page=%s&item=%s&action=%s&_wpnonce=%s">Change Status</a>',
                    $item['allergy_name'],
                    $_REQUEST['page'],
                    $item['allergy_name'],
                    'change_status',
                    $change_status_nonce,
                ),
            );

            return $this->row_actions($actions);
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
                    echo $this->column_change_status( $item, $column_name, $primary );
                    echo '</td>';
                } else {
                    echo "<td $attributes>";
                    echo $this->column_default( $item, $column_name );
                    echo $this->column_change_status( $item, $column_name, $primary );
                    echo '</td>';
                }
            }
        }
    
        public function get_columns() {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'is_active' => __('Status', 'allergens-dietary-ictoria'),
                'allergy_name' => __('Allergy name', 'allergens-dietary-ictoria'),
                'allergy_description' => __('Allergy description', 'allergens-dietary-ictoria'),
                'is_allergy' => __('Allergy or Dietary', 'allergens-dietary-ictoria'),
            );
            return $columns;
        }
    
        public function prepare_items() {
            global $wpdb;

            $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
            $result = $this->get_table_columns_and_data();
    
            $this->_column_headers = [$result['columns'], [], []];

            $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    
            $this->_column_headers = [$this->get_columns(), [], []];
    
            $this->items = $data;
        }

        public function process_quick_action(){
            if (isset($_GET['item'])){
                $nonce = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
            if (!wp_verify_nonce($nonce, 'allergens_change_status')) {
                die('nonce not verified44, action bulk change status');
            } else {
            if (isset($_GET['item'])){
                $value = array(
                    $_GET['item']
                );
                Allergens_Dietary_Ictoria_Allergen_Queries::singleActivationUpdate();
            }
            }
            }
        }

        public function process_bulk_action($data) { 
            $nonce = sanitize_text_field(wp_unslash($_POST['bulk_change_status_nonce']));
            if (!wp_verify_nonce($nonce, 'allergens_bulk_change_status')) {
                die('nonce not verified22, action bulk change status');
            } else {
                global $wpdb;

                if('change_status' === $this->current_action()){

                    Allergens_Dietary_Ictoria_Allergen_Queries::activationUpdate($data);
                }

                if ( ( isset( $data['item'] ) )
                ) {
                    if ($data['action'] == "on/off" && !is_string($data['item'])){
                        foreach ($data['item'] as $key => $value){
                            $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
                            $sql = $wpdb->get_results(
                            "SELECT * FROM $table_name WHERE is_active = '$value'"
                        );

                        if ($sql > 0){
                            Allergens_Dietary_Ictoria_Allergen_Queries::activationUpdate($data);
                            break;
                        }
                    }
                }
            }
    }
}

        public function change_status(){
            
        }

        public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$_instances[$cls])) {
            self::$_instance[$cls] = new static();
        }

        return self::$_instance[$cls];
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
    $table = Allergens_Dietary_Ictoria_Show_Allergens::getInstance();
    if (isset( $_POST['action'] ) && isset($_POST['post']) && $_POST['action'] == 'on/off'){

        $counter = 0;
        $process_item = [];
        $process_action = "";
        $process_data = [];

        $process_action = sanitize_text_field( $_POST['action'] );

        unset($_POST['action']);

        foreach ($_POST['post'] as $key => $value){
                $process_item[] = sanitize_text_field( $value );
        }

        $process_data = [
            'action' => $process_action,
            'item' => $process_item,
        ];

        $table->process_bulk_action($process_data);
    }
}else{
    if ($_SERVER['REQUEST_METHOD'] === 'GET'){
        $table = Allergens_Dietary_Ictoria_Show_Allergens::getInstance();

        $table->process_quick_action();
    }
}

