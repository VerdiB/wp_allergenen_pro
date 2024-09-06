<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class ADI_Base_Menu_Page
{
    protected $page_title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $callback;
    protected $icon_url = '';
    protected $position = null;
    protected $sections = [];

    public function __construct($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url = '', $position = null)
    {
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->capability = $capability;
        $this->menu_slug = $menu_slug;
        $this->callback = $callback;
        $this->icon_url = $icon_url;
        $this->position = $position;

        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_menu()
    {
        if ($this->is_top_level()) {
            add_menu_page(
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                [$this, 'render_page'],
                $this->icon_url,
                $this->position
            );
        } else {
            add_submenu_page(
                $this->get_parent_slug(),
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                [$this, 'render_page']
            );
        }
    }

    public function register_settings()
    {
        register_setting(
            $this->menu_slug . '_options_group',
            $this->menu_slug . '_options',
            [$this, 'validate_settings']
        );

        foreach ($this->sections as $section) {
            $section->register_section($this->menu_slug);
        }
    }

    public function render_page()
    {
        if (!current_user_can($this->capability)) {
            return;
        }

        echo '<div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        echo '<form action="options.php" method="post">';
        settings_fields($this->menu_slug . '_options_group');
        do_settings_sections($this->menu_slug);
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    public function add_section($section)
    {
        $this->sections[] = $section;
    }

    public function validate_settings($input)
    {
        // Default validation function, can be overridden by child classes
        return $input;
    }

    abstract protected function is_top_level();

    abstract protected function get_parent_slug();
}
