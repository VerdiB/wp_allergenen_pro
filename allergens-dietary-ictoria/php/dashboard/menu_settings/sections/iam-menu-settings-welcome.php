<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Menu_Settings_Welcome extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'iam_settings_welcome_section',
            __('Settings Welcome Title', 'text-domain'),
            'iam-settings'
        );

        $this->add_field(
            'iam_settings_welcome_field',
            __('Welcome Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'iam_settings_welcome_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('iam_settings_welcome_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="iam_settings_welcome_field" value="' . esc_attr($option_value) . '" />';
    }
}
