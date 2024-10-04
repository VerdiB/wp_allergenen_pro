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


    /*public function get_columns() {
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

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

        $sql_count = $wpdb->prepare(
            "SELECT COUNT(allergy_name) FROM $table_name"
        );
        $count_results = $wpdb->get_results( $sql_count , ARRAY_A );

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();

        $this->_column_headers = array(
			$columns,
			$hidden,
			$sortable,
		);

		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page('assets_per_page', 10);
		$current_page = $this->get_pagenum();
		$total_items  = $this->record_count();

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$this->items = $this->get_assets($per_page, $current_page);

        $sql_allergens = $wpdb->prepare(
            "SELECT * FROM $table_name"
        );

        $allergens_results = $wpdb->get_results( $sql_allergens , ARRAY_A );
   
        $html = '
        <form action="" method="post" enctype="multipart/form-data" class="add_allergens_form">
        <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead> 
        <tr>
        <th scope="col" id="name" class="manage-column column-name column-primary"> <input id="cb-select-all-1" name="lijst" type="checkbox">  Actief: 
        <label for="lijst">
            <span class="screen-reader-text">Alles selecteren</span>
        </label> </th>
        <th scope="col" id="name" class="manage-column column-name column-primary"> Allergenen en dieten:
        </th> 
        <th scope="col" id="name" class="manage-column column-name column-primary"> Beschrijving: </th>
        <th scope="col" id="name" class="manage-column column-name column-primary"> </th>
        <th scope="col" id="name" class="manage-column column-name column-primary"> Allergeen of dieet: </th>
        </thead>
        </form>';

        try {
            if(!empty($allergens_results)){
            foreach ($allergens_results as $row){
                $allergenOrDieet = "";
                if ($row['is_allergy'] == 1){
                    $allergenOrDieet = "Allergeen";
                }else{
                    $allergenOrDieet = "Dieet";
                }
                $isactive = "test";

                /*
                if ($row['is_active'] == 1){
                    $allergenOrDieet = "Active";
                }else{
                    $allergenOrDieet = "Inactive";
                }

                $is_checked = true;

                if ($row['is_active'] == 1){
                    $is_checked = true;
                }else{
                    $is_checked = false;
                }

                $html .= '<tr scope="row" id="post-1" class="iedit author-self level-0 post-8 type-page status-publish hentry">
                <td class="allergen-dietary_item label"><input id="cb-select-all-1" name="' . $row['allergy_name'] . '" value="' . $row['allergy_name'] . '" type="checkbox"' . ($is_checked ? 'checked' : '') . '>' . $isactive . '</td>
                <td class="allergen-dietary_item label">' . esc_html( $row['allergy_name'] ) . ':</td>
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
        $html .= '</table><br><br><button id="cb-select-all-1" type="submit">Save</button>';
        echo $html;
    }

    public function activation($data){
        global $wpdb;
        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $sql = $wpdb->prepare(
            "SELECT * FROM $table_name"
        );
        $results = $wpdb->get_results( $sql , ARRAY_A );

        //$checkbox_value = $data['list'];
        //echo $data['list'] . " Checkbox was checked. Value: " . htmlspecialchars($checkbox_value);

        /*echo "<pre>";
            foreach ($results as $row){
                
            }
        echo "</pre>";
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

    //Bulk/quick actions zijn voor volgende sprint.*/