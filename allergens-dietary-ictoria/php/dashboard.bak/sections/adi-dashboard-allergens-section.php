<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Allergens_Section extends ADI_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            'adi_allergens_section',
            __('Allergens Section Title', 'text-domain'),
            'adi-allergens'
        );

        $this->add_field(
            'adi_allergens_field',
            __('Allergens Field', 'text-domain'),
            [$this, 'field_callback'],
            ['label_for' => 'adi_allergens_field']
        );
    }

    public function field_callback($args)
    {
        $option_value = get_option('adi_allergens_field');

        echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="adi_allergens_field" value="' . esc_attr($option_value) . '" />';
    }
}
