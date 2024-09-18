<?php
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_New_Page_Title_Section_New_Section_Title extends IAM_Base_Section
{

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Add Allergen', 'allergens-dietary-ictoria'), /* set title */
            str_replace('_', '-', strtolower(__CLASS__)),
        );
    }

    public function get_section_class()
    {
        return 'iam-allergens-dietary-add-allergen';
    }

    public function section_callback()
    {
        $html = <<<HTML
        <div>
            This is the section callback content.
        </div>
        HTML;

        echo $html;
    }

}
