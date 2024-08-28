<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Section
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        // Register section in the settings
        add_action('admin_init', [$this, 'main_section_init']);
    }

    public function main_section_init()
    {
        add_settings_section(
            'adi_main_section',
            __('Main section title', 'allergens-dietary-ictoria'),
            [$this, 'section_main_callback'],
            'allergens_dietary_ictoria'
        );

        add_settings_field(
            'adi_field_pill',
            __('Pill', 'allergens-dietary-ictoria'),
            [$this, 'field_pill_callback'],
            'allergens_dietary_ictoria',
            'adi_main_section',
            [
                'label_for' => 'adi_field_pill',
                'class' => 'adi_row',
                'adi_custom_data' => 'custom',
            ]
        );
    }

    public function section_main_callback($args)
    {
        echo $this->get_section_main_html($args);
    }

    public function field_pill_callback($args)
    {
        echo $this->get_field_pill_html($args);
    }

    // Generates HTML for the section main callback
    private function get_section_main_html($args)
    {
        $id = esc_attr($args['id']);
        $content = esc_html__('Follow the white rabbit.', 'allergens_dietary_ictoria');

        return "<p id=\"$id\">$content</p>";
    }

    // Generates HTML for the field pill callback
    private function get_field_pill_html($args)
    {
        $options = get_option('allergens_dietary_ictoria_dashboard_options');
        $label_for = esc_attr($args['label_for']);
        $custom_data = esc_attr($args['adi_custom_data']);
        $red_selected = selected($options[$args['label_for']], 'red', false);
        $blue_selected = selected($options[$args['label_for']], 'blue', false);

        $html = <<<HTML
        <select id="$label_for" data-custom="$custom_data" name="allergens_dietary_ictoria_dashboard_options[$label_for]">
            <option value="red" $red_selected>Red Pill</option>
            <option value="blue" $blue_selected>Blue Pill</option>
        </select>
        <p class="description">
            You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.
        </p>
        <p class="description">
            You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.
        </p>
        HTML;

        return $html;
    }
}
