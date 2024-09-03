<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Section extends ADI_Base_Dashboard_Section
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        parent::__construct('adi_main_section', __('Main section title', 'allergens-dietary-ictoria'), 'allergens-dietary-ictoria');

        // Add specific fields for this section
        $this->add_field('adi_field_pill', __('Pill', 'allergens-dietary-ictoria'), [$this, 'field_pill_callback']);
    }

    public function field_pill_callback($args)
    {
        $options = get_option('allergens_dietary_ictoria_dashboard_options');
        $html = '<select id="' . esc_attr($args['label_for']) . '"
                    data-custom="' . esc_attr($args['adi_custom_data']) . '"
                    name="allergens_dietary_ictoria_dashboard_options[' . esc_attr($args['label_for']) . ']">
                    <option value="red" ' . selected($options[$args['label_for']], 'red', false) . '>' . esc_html__('red pill', 'allergens-dietary-ictoria') . '</option>
                    <option value="blue" ' . selected($options[$args['label_for']], 'blue', false) . '>' . esc_html__('blue pill', 'allergens-dietary-ictoria') . '</option>
                </select>';
        $html .= '<p class="description">' . esc_html__('You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.', 'allergens-dietary-ictoria') . '</p>';
        $html .= '<p class="description">' . esc_html__('You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.', 'allergens-dietary-ictoria') . '</p>';
        echo $html;
    }
}
