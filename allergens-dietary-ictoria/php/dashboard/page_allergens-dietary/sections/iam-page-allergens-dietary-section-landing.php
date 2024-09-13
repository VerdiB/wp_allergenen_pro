<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_Landing extends IAM_Base_Section
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
        require_once IAM_DIR . '/utilities/database_connect.php';
        $db = new IAM_Database_Connect();
        $options = $db->get_local_options();

        foreach ($options as $allergen => $value) {
            $title = $value['title'];
            $description = $title . ' description text';

            echo $db->insert_allergen($title, $description);

            // echo <<<HTML
            // <pre>
            //     $title:
            //     $description
            // </pre>
            // HTML;
        }
    }

    // public function section_callback()
    // {

    //     global $wpdb;
    //     $row = $wpdb->get_results("SELECT * FROM wp_allergens_dietary_ictoria_allergy");
    //     // $row_dump = var_dump($row);
    //     $options = Allergens_Dietary_Ictoria_Functions::default_options();

    //     $section_class = str_replace('_', '-', strtolower(__CLASS__));
    //     /* PHP Heredoc
    //      * https://www.phptutorial.net/php-tutorial/php-heredoc/
    //      */
    //     $html = <<<HTML
    //     <div class="iam-plugin-section $section_class">
    //         Hello!
    //     HTML;

    //     foreach ($options as $allergen => $value) {
    //         $output = $value['title'];

    //         $html .= <<<HTML
    //         <pre>
    //             $output
    //         </pre>
    //         HTML;
    //     }

    //     $html .= <<<HTML
    //     </div>
    //     HTML;

    //     echo $html;
    // }

}
