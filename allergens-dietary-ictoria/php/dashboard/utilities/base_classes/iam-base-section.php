<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class IAM_Base_Section
{
    protected $section_id;
    protected $section_title;
    protected $section_class;
    protected $enable_options_group;
    protected $fields = [];

    private static $instances = [];

    public static function instance()
    {
        $calledClass = static::class;
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }

    public function __construct($section_id, $section_title, $section_class, $enable_options_group = true)
    {
        $this->section_id = $section_id;
        $this->section_title = $section_title;
        $this->section_class = $section_class;
        $this->enable_options_group = $enable_options_group;
    }

    public function register_section($menu_slug)
    {
        add_settings_section(
            $this->section_id,
            $this->section_title,
            [$this, 'section_callback'],
            $menu_slug,
            ['before_section' => '<div class="' . $this->section_class . '">', 'after_section' => '</div>']
        );

        foreach ($this->fields as $field) {
            add_settings_field(
                $field['id'],
                $field['title'],
                $field['callback'],
                $menu_slug,
                $this->section_id,
                $field['args']
            );

            if ($this->enable_options_group) {
                register_setting($menu_slug . '_options_group', $field['id']);
            }
        }
    }

    public function add_field($field_id, $field_title, $callback, $args = [])
    {
        $this->fields[] = [
            'id' => $field_id,
            'title' => $field_title,
            'callback' => $callback,
            'args' => $args,
        ];
    }

    public function section_callback()
    {
        echo '<p>Section description here.</p>';
    }
}
