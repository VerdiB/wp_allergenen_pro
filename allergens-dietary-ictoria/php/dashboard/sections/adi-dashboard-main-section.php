<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Section extends ADI_Base_Section
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
        parent::__construct('adi_main_section', 'Main Section Title');

        $this->add_field(
            'example_field',
            'Example Field',
            [$this, 'example_field_callback'],
            ['label_for' => 'example_field']
        );
    }

    public function example_field_callback($args)
    {
        $option_value = get_option('adi_settings_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="adi_settings_field" value="' . esc_attr($option_value) . '" />';
    }
}
