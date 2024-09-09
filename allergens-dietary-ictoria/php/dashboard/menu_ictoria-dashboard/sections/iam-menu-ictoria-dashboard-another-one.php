<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Menu_Ictoria_Dashboard_Another_One extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'iam_ictoria_dashboard_another_one_section',
            __('Another one Title', 'text-domain'),
            'iam-dashboard'
        );

        $this->add_field(
            'iam_ictoria_dashboard_another_one_field',
            __('Another one Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'iam_ictoria_dashboard_another_one_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('iam_ictoria_dashboard_another_one_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="iam_ictoria_dashboard_another_one_field" value="' . esc_attr($option_value) . '" />';
    }
}
