<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


class Allergens_Dietary_Ictoria_Dashboard_Section_Main {

    // Singleton instance
    private static $instance = null;
    // Singleton instance method
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor
    private function __construct() {
        // Register actions
        add_action( 'admin_init', [ $this, 'section_main_init' ] );
    }

    // Initialize settings
    public function section_main_init() {
        // Register a new section in the "Allergens_Dietary_Ictoria" page.
        add_settings_section(
            'allergens_dietary_ictoria_section_main',
            __( 'Main section title', 'Allergens_Dietary_Ictoria' ),
            [ $this, 'section_main_callback' ],
            'Allergens_Dietary_Ictoria'
        );

        // Register a new field in the "allergens_dietary_ictoria_section_main" section, inside the "Allergens_Dietary_Ictoria" page.
        add_settings_field(
            'Allergens_Dietary_Ictoria_field_pill',
            __( 'Pill', 'Allergens_Dietary_Ictoria' ),
            [ $this, 'field_pill_callback' ],
            'Allergens_Dietary_Ictoria',
            'allergens_dietary_ictoria_section_main',
            [
                'label_for'         => 'Allergens_Dietary_Ictoria_field_pill',
                'class'             => 'Allergens_Dietary_Ictoria_row',
                'Allergens_Dietary_Ictoria_custom_data' => 'custom',
            ]
        );
    }

    // Main section callback function
    public function section_main_callback( $args ) {
        ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'Allergens_Dietary_Ictoria' ); ?></p>
        <?php
    }

    // Pill field callback function
    public function field_pill_callback( $args ) {
        // Get the value of the setting we've registered with register_setting()
        $options = get_option( 'allergens_dietary_ictoria_dashboard_options' );
        ?>
        <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['Allergens_Dietary_Ictoria_custom_data'] ); ?>"
            name="allergens_dietary_ictoria_dashboard_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
            <option value="red" 
                <?php echo isset( $options[ $args['label_for'] ] ) 
                    ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) 
                    : ( '' ); 
                ?>
            >
                <?php esc_html_e( 'red pill', 'Allergens_Dietary_Ictoria' ); ?>
            </option>
            <option value="blue" 
                <?php echo isset( $options[ $args['label_for'] ] ) 
                    ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) 
                    : ( '' ); 
                ?>
            >
                <?php esc_html_e( 'blue pill', 'Allergens_Dietary_Ictoria' ); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e( 'You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'Allergens_Dietary_Ictoria' ); ?>
        </p>
        <p class="description">
            <?php esc_html_e( 'You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'Allergens_Dietary_Ictoria' ); ?>
        </p>
        <?php
    }
}

// Instantiate the class
Allergens_Dietary_Ictoria_Dashboard_Section_Main::get_instance();
