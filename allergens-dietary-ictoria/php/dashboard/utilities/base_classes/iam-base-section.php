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

    public function __construct($section_id, $section_title, $section_class)
    {
        $this->section_id = $section_id;
        $this->section_title = $section_title;
        $this->section_class = $section_class;
    }

    public function register_section($menu_slug)
    {
        // You can choose to add section-specific functionality here if needed
    }

    public function section_callback()
    {
        // Default callback, should be overridden in subclasses
        echo '<p>Section description here.</p>';
    }

    public function get_section_id()
    {
        return $this->section_id;
    }

    public function get_section_title()
    {
        return $this->section_title;
    }

    public function get_section_class()
    {
        return $this->section_class;
    }
}
