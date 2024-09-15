<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_Manage_Allergens extends IAM_Base_Section
{

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Manage Allergens', 'allergens-dietary-ictoria'),
            str_replace('_', '-', strtolower(__CLASS__)),
            true
        );
    }

    public function get_section_class()
    {
        return 'iam-allergens-dietary-manage-allergens';
    }

    public function section_callback()
    {
        $allergens = IAM_Database_Connect::get_allergens_with_attachments();

        $html_allergens = '';
        $allergen_class = $this->get_section_class() . '-all-allergens-allergen';

        foreach ($allergens as $allergen => $value) {

            $allergen_name = $value['allergen_name'];
            $allergen_icon = $value['icon_url'];

            $html_allergens .= <<<HTML
            <div class="$allergen_class">
                <img src="$allergen_icon" alt="$allergen" title="$allergen_name" width="50" height="50">

                <!-- <span>$allergen_name</span> -->
            </div>
            HTML;

        }

        $wrapper_class = $this->get_section_class() . '-all-allergens';
        $html = <<<HTML
        <p>Disable/enable, edit, add or remove allergens</p>
        <div class="$wrapper_class">
            $html_allergens
        </div>
        HTML;

        echo $html;
    }

}
