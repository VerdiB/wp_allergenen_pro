<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Menu_Ictoria_Dashboard_Welcome extends ADI_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'adi_main_section',
            __('Main Section Title', 'text-domain'),
            'adi-dashboard'
        );

        $this->add_field(
            'adi_dashboard_field',
            __('Dashboard Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'adi_dashboard_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('adi_dashboard_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="adi_dashboard_field" value="' . esc_attr($option_value) . '" />';
    }
}
