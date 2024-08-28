<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Allergens_Dietary_Ictoria_Dashboard_Section_Main {

    private static $instance = null;

    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register section in the settings
        add_action('admin_init', [$this, 'section_main_init']);
    }

    public function section_main_init() {
        add_settings_section(
            'allergens_dietary_ictoria_section_main',
            __('Main section title', 'allergens-dietary-ictoria'),
            [$this, 'section_main_callback'],
            'allergens_dietary_ictoria'
        );

        add_settings_field(
            'Allergens_Dietary_Ictoria_field_pill',
            __('Pill', 'allergens-dietary-ictoria'),
            [$this, 'field_pill_callback'],
            'allergens_dietary_ictoria',
            'allergens_dietary_ictoria_section_main',
            [
                'label_for' => 'Allergens_Dietary_Ictoria_field_pill',
                'class' => 'Allergens_Dietary_Ictoria_row',
                'Allergens_Dietary_Ictoria_custom_data' => 'custom',
            ]
        );
    }

    public function section_main_callback($args) {
        echo '<pre>Section Callback Executed</pre>';  // Debugging line

        ?>
        <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Follow the white rabbit.', 'allergens-dietary-ictoria'); ?></p>
        <?php
    }

    public function field_pill_callback($args) {
        $options = get_option('allergens_dietary_ictoria_dashboard_options');
        ?>
        <select
            id="<?php echo esc_attr($args['label_for']); ?>"
            data-custom="<?php echo esc_attr($args['Allergens_Dietary_Ictoria_custom_data']); ?>"
            name="allergens_dietary_ictoria_dashboard_options[<?php echo esc_attr($args['label_for']); ?>]">
            <option value="red" <?php selected($options[$args['label_for']], 'red'); ?>><?php esc_html_e('red pill', 'allergens-dietary-ictoria'); ?></option>
            <option value="blue" <?php selected($options[$args['label_for']], 'blue'); ?>><?php esc_html_e('blue pill', 'allergens-dietary-ictoria'); ?></option>
        </select>
        <p class="description">
            <?php esc_html_e('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'allergens-dietary-ictoria'); ?>
        </p>
        <p class="description">
            <?php esc_html_e('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'allergens-dietary-ictoria'); ?>
        </p>
        <?php
    }
}
