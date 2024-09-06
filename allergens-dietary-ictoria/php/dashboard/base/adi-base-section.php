<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class ADI_Base_Section
{
    protected $section_id;
    protected $section_title;
    protected $fields = [];

    public function __construct($section_id, $section_title)
    {
        $this->section_id = $section_id;
        $this->section_title = $section_title;
    }

    public function register_section($menu_slug)
    {
        add_settings_section(
            $this->section_id,
            $this->section_title,
            [$this, 'section_callback'],
            $menu_slug
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

            register_setting($menu_slug . '_options_group', $field['id']);
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
