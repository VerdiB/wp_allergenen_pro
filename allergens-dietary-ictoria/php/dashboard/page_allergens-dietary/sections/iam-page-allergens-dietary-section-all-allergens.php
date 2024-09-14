<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_All_Allergens extends IAM_Base_Section
{

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('All Allergens', 'allergens-dietary-ictoria'),
            str_replace('_', '-', strtolower(__CLASS__)),
            true
        );

        require_once IAM_DIR . '/utilities/database_connect.php';
    }

    public function section_callback()
    {
        $allergens = IAM_Database_Connect::get_allergens_with_attachments();

        $html_allergens = '';
        $allergen_class = str_replace('_', '-', strtolower(__CLASS__)) . '-allergen';

        foreach ($allergens as $allergen => $value) {

            $allergen_name = $value['allergen_name'];
            $allergen_icon = $value['icon_url'];

            $html_allergens .= <<<HTML
            <div class="$allergen_class">
                <img src="$allergen_icon" alt="$allergen" width="50" height="50">

                <span>$allergen_name</span>
            </div>
            HTML;

        }

        $content_class = str_replace('_', '-', strtolower(__CLASS__));

        $html = <<<HTML
        <div class="$content_class">
            $html_allergens
        </div>
        HTML;

        echo $html;
    }

}
