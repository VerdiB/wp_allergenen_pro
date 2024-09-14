<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Ictoria_Dashboard_Section_Dashboard extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            '',
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
            This is text
        HTML;

        echo $html;

    }

}
