<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Settings_Section_Another_One extends IAM_Base_Section
{
    private static $field_name = '_' . 'field_name';

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Another One Title in settings', 'text-domain'),
            'iam-settings-section-another-one'
        );

    }

    public function section_callback()
    {
        echo '<p>Section description here.</p>';

    }

    public function field_callback($args)
    {
        $option_value = get_option(strtolower(__CLASS__ . self::$field_name));

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . strtolower(__CLASS__ . self::$field_name) . '" value="' . esc_attr($option_value) . '" />';
    }

}
