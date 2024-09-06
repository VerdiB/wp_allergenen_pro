<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Settings_Section extends ADI_Base_Section
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
        parent::__construct(
            'adi_settings_section',
            __('Settings Section Title', 'text-domain'),
            'adi-dashboard-settings'
        );

        $this->add_field(
            'adi_settings_field',
            __('Settings Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'adi_settings_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('adi_settings_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="adi_settings_field" value="' . esc_attr($option_value) . '" />';
    }
}
