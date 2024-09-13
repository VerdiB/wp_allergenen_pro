<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Ictoria_Dashboard_Section_Allergens_Dietary extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Allergens & Dietary Plugin', 'text-domain'),
            str_replace('_', '-', strtolower(__CLASS__))
        );
    }

    public function section_callback()
    {
        $section_class = str_replace('_', '-', strtolower(__CLASS__));
        $allergens_dietary_url = admin_url('admin.php?page=iam-allergens-dietary');
        /* PHP Heredoc
         * https://www.phptutorial.net/php-tutorial/php-heredoc/
         */
        $html = <<<HTML
        <div class="iam-dashboard-section $section_class">
            <a href="$allergens_dietary_url">
                <button>check it out</button>
            </a>
        </div>
        HTML;

        echo $html;

    }

}
