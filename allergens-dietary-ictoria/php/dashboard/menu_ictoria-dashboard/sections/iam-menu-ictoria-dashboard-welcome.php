<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Menu_Ictoria_Dashboard_Welcome extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'iam_ictoria_dashboard_welcome_section',
            __('Welcome Title', 'text-domain'),
            'iam-dashboard'
        );

        $this->add_field(
            'iam_ictoria_dashboard_welcome_field',
            __('Welcome Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'iam_ictoria_dashboard_welcome_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('iam_ictoria_dashboard_welcome_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="iam_ictoria_dashboard_welcome_field" value="' . esc_attr($option_value) . '" />';
    }
}
