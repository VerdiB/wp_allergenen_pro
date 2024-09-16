<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class IAM_Base_Page
{
    protected $page_title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $callback;
    protected $icon_url = '';
    protected $position = null;
    protected $sections = [];

    private static $instances = [];

    public static function instance()
    {
        $calledClass = static::class;
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }

    public function __construct($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url = '', $position = null)
    {
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->capability = $capability;
        $this->menu_slug = $menu_slug;
        $this->callback = $callback;
        $this->icon_url = $icon_url;
        $this->position = $position;

        // Register autoload function for sections
        spl_autoload_register([$this, 'autoload']);
        add_action('admin_menu', [$this, 'register_menu']);
    }

    public function autoload($class_name)
    {
        if (strpos($class_name, 'IAM_Page') === 0) {
            // Get the calling class name using Reflection
            $reflection = new ReflectionClass($this);
            $class_file = $reflection->getFileName();
            $parent_class_dir = basename(dirname($class_file));

            // Convert class name to file name
            $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';

            // Construct the file path
            $file_path = IAM_DIR . '/' . $parent_class_dir . '/sections/' . $file_name;

            // Load the file if it exists
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
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
            add_submenu_page(
                $this->menu_slug,
                $this->page_title,
                'Dashboard',
                $this->capability,
                $this->menu_slug,
                [$this, 'render_page']
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

    public function render_page()
    {
        if (!current_user_can($this->capability)) {
            return;
        }

        echo '<div class="wrap">';

        // Call the method responsible for rendering sections
        $this->render_sections();

        echo '</div>';
    }

    protected function render_sections($enable_header = true)
    {
        foreach ($this->sections as $section) {
            // Ensure the section is an instance of IAM_Base_Section
            if ($section instanceof IAM_Base_Section) {
                // Call the section's callback method
                echo '<div class="' . $section->get_section_class() . '">';
                if ($enable_header) {
                    echo '<h2>' . $section->get_section_title() . '</h2>';
                }
                $section->section_callback();
                echo '</div>';
            }
        }
    }

    public function add_section($section)
    {
        // Ensure no duplicate sections are added
        foreach ($this->sections as $existing_section) {
            if ($existing_section->get_section_id() === $section->get_section_id()) {
                return; // Section already exists
            }
        }
        $this->sections[] = $section;
    }

    abstract protected function is_top_level();
    abstract protected function get_parent_slug();
}
