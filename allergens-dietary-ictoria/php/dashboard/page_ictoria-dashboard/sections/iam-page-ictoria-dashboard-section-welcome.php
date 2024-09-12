<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Ictoria_Dashboard_Section_Welcome extends IAM_Base_Section
{

    private static $field_name = '_' . 'field_name';

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Welcome Title', 'text-domain'),
            'iam-dashboard-section-welcome'
        );

        $this->add_field(
            strtolower(__CLASS__ . self::$field_name),
            __('Welcome Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => strtolower(__CLASS__ . self::$field_name)]
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
