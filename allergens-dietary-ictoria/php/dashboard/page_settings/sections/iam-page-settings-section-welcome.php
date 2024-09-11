<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Settings_Section_Welcome extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'iam_page_settings_section_welcome',
            __('Welcome Title', 'text-domain'),
            'iam-settings'
        );

        $this->add_field(
            'iam_page_settings_section_welcome_field',
            __('Welcome Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'iam_page_settings_section_welcome_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('iam_page_settings_section_welcome_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="iam_page_settings_section_welcome_field" value="' . esc_attr($option_value) . '" />';
    }
}
