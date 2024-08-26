<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


class Allergens_Dietary_Ictoria_Dashboard {

    // Singleton instance
    private static $instance = null;

    // Constructor
    private function __construct() {
        // Register actions
        add_action( 'admin_init', [ $this, 'settings_init' ] );
        add_action( 'admin_menu', [ $this, 'options_page' ] );
    }

    // Singleton instance method
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Initialize settings
    public function settings_init() {
        // Register a new setting for "Allergens_Dietary_Ictoria" page.
        register_setting( 'Allergens_Dietary_Ictoria', 'Allergens_Dietary_Ictoria_options' );

        // Register a new section in the "Allergens_Dietary_Ictoria" page.
        add_settings_section(
            'Allergens_Dietary_Ictoria_section_developers',
            __( 'The Matrix has you.', 'Allergens_Dietary_Ictoria' ),
            [ $this, 'section_developers_callback' ],
            'Allergens_Dietary_Ictoria'
        );

        // Register a new field in the "Allergens_Dietary_Ictoria_section_developers" section, inside the "Allergens_Dietary_Ictoria" page.
        add_settings_field(
            'Allergens_Dietary_Ictoria_field_pill',
            __( 'Pill', 'Allergens_Dietary_Ictoria' ),
            [ $this, 'field_pill_callback' ],
            'Allergens_Dietary_Ictoria',
            'Allergens_Dietary_Ictoria_section_developers',
            [
                'label_for'         => 'Allergens_Dietary_Ictoria_field_pill',
                'class'             => 'Allergens_Dietary_Ictoria_row',
                'Allergens_Dietary_Ictoria_custom_data' => 'custom',
            ]
        );
    }

    // Developers section callback function
    public function section_developers_callback( $args ) {
        ?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'Allergens_Dietary_Ictoria' ); ?></p>
        <?php
    }

    // Pill field callback function
    public function field_pill_callback( $args ) {
        // Get the value of the setting we've registered with register_setting()
        $options = get_option( 'Allergens_Dietary_Ictoria_options' );
        ?>
        <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['Allergens_Dietary_Ictoria_custom_data'] ); ?>"
            name="Allergens_Dietary_Ictoria_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
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

    // Add the top level menu page
    public function options_page() {
        add_menu_page(
            'Allergens Dietary Ictoria',
            'Allergens & Dietary Options',
            'manage_options',
            'Allergens_Dietary_Ictoria',
            [ $this, 'options_page_html' ]
        );
    }

    // Top level menu callback function
    public function options_page_html() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Add error/update messages
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'Allergens_Dietary_Ictoria_messages', 'Allergens_Dietary_Ictoria_message', __( 'Settings Saved', 'Allergens_Dietary_Ictoria' ), 'updated' );
        }

        // Show error/update messages
        settings_errors( 'Allergens_Dietary_Ictoria_messages' );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                // Output security fields for the registered setting "Allergens_Dietary_Ictoria"
                settings_fields( 'Allergens_Dietary_Ictoria' );
                // Output setting sections and their fields
                do_settings_sections( 'Allergens_Dietary_Ictoria' );
                // Output save settings button
                submit_button( 'Save Settings' );
                ?>
            </form>
        </div>
        <?php
    }
}

// Instantiate the class
Allergens_Dietary_Ictoria_Dashboard::get_instance();
