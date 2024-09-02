<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class ADI_Base_Dashboard_Section
{
    protected $section_id;
    protected $section_title;
    protected $page_slug;
    protected $fields = [];

    public function __construct($section_id, $section_title, $page_slug)
    {
        $this->section_id = $section_id;
        $this->section_title = $section_title;
        $this->page_slug = $page_slug;

        add_action('admin_init', [$this, 'add_settings_section']);
    }

    public function add_settings_section()
    {
        add_settings_section(
            $this->section_id,
            $this->section_title,
            [$this, 'section_callback'],
            $this->page_slug
        );

        foreach ($this->fields as $field) {
            $this->add_settings_field($field);
        }
    }

    public function section_callback($args)
    {
        echo '<p>' . esc_html__('This is a generic section description.', 'allergens-dietary-ictoria') . '</p>';
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

    private function add_settings_field($field)
    {
        add_settings_field(
            $field['id'],
            $field['title'],
            $field['callback'],
            $this->page_slug,
            $this->section_id,
            $field['args']
        );
    }
}
