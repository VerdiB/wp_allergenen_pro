<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class ADI_Dashboard_Allergens_Page extends ADI_Base_Menu_Page
{
    public function __construct()
    {
        parent::__construct(
            'Allergens & Dietary Dashboard',
            'Allergens & Dietary',
            'manage_options',
            'adi-allergens',
            [$this, 'render_page'],
            '',
            null
        );

        $this->add_section(new ADI_Dashboard_Allergens_Section());
    }

    protected function is_top_level()
    {
        return false;
    }

    protected function get_parent_slug()
    {
        return 'adi-dashboard';
    }
}
