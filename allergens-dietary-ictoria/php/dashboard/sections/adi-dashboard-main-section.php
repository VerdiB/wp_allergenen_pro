<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Main_Section extends ADI_Base_Dashboard_Section
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
            __('Main Section', 'allergens-dietary-ictoria-dashboard'), // Page title
            __('Main Section Title', 'allergens-dietary-ictoria-dashboard'), // Menu title
            'manage_options', // Capability
            'allergens-dietary-ictoria-dashboard', // Menu slug
            [$this, 'render_page']// Callback for rendering
        );

        // Hooks for registering settings and adding menu
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'admin_init_hooks']); // Important to call this

        // Initialize fields after section creation
        add_action('admin_init', [$this, 'initialize_fields']);
    }

    public function initialize_fields()
    {
        // We add fields directly to this page
        parent::add_field(
            'adi_field_pill', // Field ID
            __('Pill', 'allergens-dietary-ictoria-dashboard'), // Field Title
            [$this, 'field_pill_callback'], // Callback
            [
                'label_for' => 'adi-field-pill',
                'class' => 'adi-field-row',
                'adi_custom_data' => 'custom',
            ]
        );
    }

    public function field_pill_callback($args)
    {
        // Retrieve the current options from the database
        $options = get_option($this->menu_slug . '_options');

        // Ensure $options is an array to avoid warnings
        if (!is_array($options)) {
            $options = [];
        }

        $label_for = esc_attr($args['label_for']);
        $custom_data = esc_attr($args['adi_custom_data']);

        // Check which option is selected
        $red_selected = selected(isset($options[$label_for]) && $options[$label_for] === 'red', true, false);
        $blue_selected = selected(isset($options[$label_for]) && $options[$label_for] === 'blue', true, false);

        // Render the HTML for the select field
        $html = <<<HTML
        <select id="$label_for" data-custom="$custom_data" name="{$this->menu_slug}_options[$label_for]">
            <option value="red" $red_selected>Red Pill</option>
            <option value="blue" $blue_selected>Blue Pill</option>
        </select>
        <p class="description">
            You take the blue pill and the story ends. You wake in your bed and you believe whatever you want to believe.
        </p>
        <p class="description">
            You take the red pill and you stay in Wonderland and I show you how deep the rabbit-hole goes.
        </p>
        HTML;

        echo $html;
    }
}
