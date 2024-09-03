<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class ADI_Base_Dashboard_Page
{
    protected $page_title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $callback;
    protected $icon_url = '';
    protected $position = null;

    public function __construct($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url = '', $position = null)
    {
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->capability = $capability;
        $this->menu_slug = $menu_slug;
        $this->callback = $callback;
        $this->icon_url = $icon_url;
        $this->position = $position;

        // add_action('admin_menu', [$this, 'add_menu_page']);
        // add_action('admin_init', [$this, 'admin_init_hooks']);
    }

    public function add_menu_page()
    {
        add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            [$this, 'render_page'],
            $this->icon_url,
            $this->position
        );
    }

    public function render_page()
    {
        if (!current_user_can($this->capability)) {
            return;
        }

        settings_errors('allergens_dietary_ictoria_messages');
        echo '<div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        echo '<form action="options.php" method="post">';
        settings_fields($this->menu_slug);
        do_settings_sections($this->menu_slug);
        submit_button('Save Settings');
        echo '</form>';
        echo '</div>';
    }

    public function admin_init_hooks()
    {
        // Register a new setting for this page
        register_setting($this->menu_slug, $this->menu_slug . '_options', [$this, 'validate_settings']);

        // Additional initialization tasks specific to this page can be added here by child classes
    }

    public function validate_settings($input)
    {
        // Default validation function, can be overridden by child classes
        return $input;
    }
}
