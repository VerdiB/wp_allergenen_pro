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
            'adi_settings_section', // Section ID
            __('Settings Section Title', 'text-domain'), // Section Title
            'adi-settings-page' // Page slug this section belongs to
        );

        // Add fields specific to this section
        $this->add_field(
            'adi_settings_field', // Field ID
            __('Settings Field', 'text-domain'), // Field Title
            [$this, 'field_callback'], // Callback to render the field
            ['label_for' => 'adi_settings_field']// Additional arguments
        );
    }

    public function field_callback($args)
    {
        // Field rendering logic here
        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="adi_settings_field" value="" />';
    }
}
