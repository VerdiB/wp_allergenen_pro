<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Ictoria_Dashboard_Section_Another_One extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'iam_page_ictoria_dashboard_section_another_one',
            __('Another one Title', 'text-domain'),
            'iam-dashboard'
        );

        $this->add_field(
            'iam_page_ictoria_dashboard_section_another_one_field',
            __('Another one Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'iam_page_ictoria_dashboard_section_another_one_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('iam_page_ictoria_dashboard_section_another_one_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="iam_page_ictoria_dashboard_section_another_one_field" value="' . esc_attr($option_value) . '" />';
    }
}
