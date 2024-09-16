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

    public function get_section_class()
    {
        return 'iam-dashboard-hero';
    }

    public function section_callback()
    {
        /* PHP Heredoc
         * https://www.phptutorial.net/php-tutorial/php-heredoc/
         */
        $html = <<<HTML
        <div class="iam-dashboard-hero-item">
            <div class="iam-dashboard-hero-item-content">
                <div class="iam-dashboard-hero-item-content-header">
                    <h2>Allergens & Dietary plugin</h2>
                    <p>This is the description for the allergen plugin. And this is some more filler text, maybe lorem ipsum would be better.</p>
                </div>

                <div class="iam-dashboard-hero-item-content-footer">
                    <span>&euro; 0,00</span>
                    <button class="iam-dashboard-hero-button">Install now</button>
                </div>
            </div>

            <img src="https://placehold.co/128" class="iam-dashboard-hero-item-img" alt="Placeholder image">
        </div>
        HTML;

        echo $html;

    }

}
