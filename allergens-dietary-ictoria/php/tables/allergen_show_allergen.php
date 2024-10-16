<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . '/wp-admin/includes/class-wp-list-table.php');
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
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

class Allergens_Dietary_Ictoria_Show_Allergens extends WP_List_Table
{

    private static $_instance = [];
    private function __construct()
    {
        parent::__construct([
            'singular' => 'item',
            'plural' => 'items',
            'ajax' => false,
        ]);
    }

    private $table_action_options = ['change_status', 'delete'];


    public function get_table_columns_and_data()
    {

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
            'data' => $allergy_names,
        ];

    }

    public function column_default($item, $column_name)
    {
        $translationOfAllergenNumbers = "Diet";
        $translationOfIsActiveNumbers = "Inactive";

        if ($item['is_allergy'] == 1) {
            $translationOfAllergenNumbers = "Allergy";
        }

        if ($item['is_active'] == 1) {
            $translationOfIsActiveNumbers = "Active";
        }

        switch ($column_name) {
            case 'allergy_name':
                return esc_html($item[$column_name]);
            case 'allergy_description':
                return esc_html($item[$column_name]);
            case 'is_allergy':
                return $translationOfAllergenNumbers;
            case 'is_active':
                return $translationOfIsActiveNumbers;
            default:
                return print_r($item, true);
        }
    }

    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="post[]" value="%s"/>', $item['allergy_name']);
    }

    public function single_row($item)
    {
        $this->column_location_id($item);
        echo '<tr>';
        $this->single_row_columns($item);
        echo '</tr>';
    }

    public function handle_row_actions($item, $column_name, $primary)
    {
        if ($primary !== $column_name) {
            return '';
        }
        $valid_actions = $this->table_action_options;

        $action_links = array();
        foreach ($valid_actions as $action) {
            $action_links[$action] = $this->build_action_url($action, $item);
        }

        return $this->row_actions($action_links);
    }

    private function build_action_url($action, $item)
    {
        $color = "black";
        $colorboolean = 0;

        if (esc_attr($action) == "delete"){
            $is_default = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();
            $colorboolean = $is_default::is_default_allergen($item['allergy_name']);
        }else{
            $colorboolean = 0;
        }

        if ($colorboolean == 1){
            $color = "grey";
        }else{
            if (esc_attr($action) == "delete"){
                $color = "red";
            }else{
                $color = "blue";
            }
        }
        
        return sprintf(
            '<a style="color: ' . $color . ';" href="?page=%s&item=%s&action=%s&_wpnonce=%s">%s</a>',
            esc_attr($_REQUEST['page']),
            esc_attr($item['allergy_name']),
            esc_attr($action),
            wp_create_nonce('allergens_' . $action),
            ucfirst(str_replace('_', ' ', $action)),

        );
    }


    public function get_bulk_actions()
    {
        $actions = array();
        $actions['change_status'] = __('Change status', 'allergens-dietary-ictoria');
        $actions['delete'] = __('Delete', 'allergens-dietary-ictoria');
        return $actions;
    }


    protected function single_row_columns($item)
    {

        list($columns, $hidden, $sortable, $primary) = $this->get_column_info();

        foreach ($columns as $column_name => $column_display_name) {
            $classes = "$column_name column-$column_name";
            if ($primary === $column_name) {
                $classes .= ' has-row-actions column-primary';
            }

            if (in_array($column_name, $hidden, true)) {
                $classes .= ' hidden';
            }

            $data = 'data-colname="' . esc_attr(wp_strip_all_tags($column_display_name)) . '"';

            $attributes = "class='$classes' $data";

            if ('cb' === $column_name) {
                echo '<th scope="row" class="check-column">';
                echo $this->column_cb($item);
                echo '</th>';
            } elseif (method_exists($this, '_column_' . $column_name)) {
                echo call_user_func(
                    array($this, '_column_' . $column_name),
                    $item,
                    $classes,
                    $data,
                    $primary
                );
            } elseif (method_exists($this, 'column_' . $column_name)) {
                echo "<td $attributes>";
                echo call_user_func(array($this, 'column_' . $column_name), $item);
                echo $this->handle_row_actions($item, $column_name, $primary);
                echo '</td>';
            } else {
                echo "<td $attributes>";
                echo $this->column_default($item, $column_name);
                echo $this->handle_row_actions($item, $column_name, $primary);
                echo '</td>';
            }
        }
    }

    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'allergy_name' => __('Allergy name', 'allergens-dietary-ictoria'),
            'allergy_description' => __('Allergy description', 'allergens-dietary-ictoria'),
            'is_allergy' => __('Allergy or Dietary', 'allergens-dietary-ictoria'),
            'is_active' => __('Status', 'allergens-dietary-ictoria'),

        );
        return $columns;
    }

    public function prepare_items()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $result = $this->get_table_columns_and_data();

        $this->_column_headers = [$result['columns'], [], []];

        $data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        $this->_column_headers = [$this->get_columns(), [], []];

        $this->items = $data;
    }

    public function process_quick_action()
    {
        if (isset($_GET['action']) && isset($_GET['item'])) {
            $item = sanitize_text_field($_GET['item']);
            $action = sanitize_text_field($_GET['action']);
            $nonce = filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Verify nonce based on action
            if ($action === 'change_status' && !wp_verify_nonce($nonce, 'allergens_change_status')) {
                wp_die('Security check failed for changing status!');
            } elseif ($action === 'delete' && !wp_verify_nonce($nonce, 'allergens_delete')) {
                wp_die('Security check failed for deletion!');
            }

            // Perform action based on case
            switch ($action) {
                case 'change_status':
                    Allergens_Dietary_Ictoria_Allergen_Queries::singleActivationUpdate();
                    break;
                case 'delete':
                    Allergens_Dietary_Ictoria_Allergen_Queries::delete_allergen_by_name($item);
                    break;
            }
        }
    }

    public function process_bulk_action($data)
    {
        // Check if nonce is set and not empty
        if (isset($_GET['_wpnonce']) && !empty($_GET['_wpnonce'])) {
            $nonce = filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $action = $this->current_action();
            foreach ($this->table_action_options as $bulk_action) {
                if ($action === $bulk_action) {
                    $nonce_action = 'bulk_' . $bulk_action;
                    break;
                }
            }
            // Verify the nonce with the correct action
            if (!wp_verify_nonce($nonce, $nonce_action)) {
                wp_die('Invalid token.');
            }
        }

        if (!isset($data['item'])) {
            return;
        }

        $action = $this->current_action();
        switch ($action) {
            case 'change_status':
                Allergens_Dietary_Ictoria_Allergen_Queries::activationUpdate($data);
                break;
            case 'delete':
                foreach ($data['item'] as $allergy_name) {
                    $allergy_name = sanitize_text_field($allergy_name);
                    Allergens_Dietary_Ictoria_Allergen_Queries::delete_allergen_by_name($allergy_name);
                }
                break;
        }
    }

    public function change_status()
    {

    }

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(self::$_instances[$cls])) {
            self::$_instance[$cls] = new static();
        }

        return self::$_instance[$cls];
    }

    public function table_page()
    {
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
    if (isset($_POST['action']) && isset($_POST['post'])) {

        $counter = 0;
        $process_item = [];
        $process_action = "";
        $process_data = [];

        $process_action = sanitize_text_field($_POST['action']);

        unset($_POST['action']);

        foreach ($_POST['post'] as $key => $value) {
            $process_item[] = sanitize_text_field($value);
        }

        $process_data = [
            'action' => $process_action,
            'item' => $process_item,
        ];

        $table->process_bulk_action($process_data);
    }
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $table = Allergens_Dietary_Ictoria_Show_Allergens::getInstance();

        $table->process_quick_action();
    }
}

