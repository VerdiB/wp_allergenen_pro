<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary_Section_Add_Allergen extends IAM_Base_Section
{

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Add Allergen', 'allergens-dietary-ictoria'),
            str_replace('_', '-', strtolower(__CLASS__)),
            true
        );
    }

    public function section_callback()
    {
        if (!class_exists('Allergens_Dietary_Ictoria_Form')) {
            require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
        }
        Allergens_Dietary_Ictoria_Form::setFormType(FormType::ALLERGENS);
        $response = Allergens_Dietary_Ictoria_Form::getInstance()->showForm();

        // echo '<pre>';
        // var_dump($response);
        // echo '</pre>';

        $html_allergenName_id = $response['fields']['allergen_name']['id'];
        $html_allergenName_label = $response['fields']['allergen_name']['label'];
        $html_allergenName_value = $response['fields']['allergen_name']['value'];

        $html_allergenDescription_id = $response['fields']['allergen_description']['id'];
        $html_allergenDescription_label = $response['fields']['allergen_description']['label'];
        $html_allergenDescription_value = $response['fields']['allergen_description']['value'];

        $html_allergenIcon_id = $response['fields']['allergen_icon']['id'];
        $html_allergenIcon_label = $response['fields']['allergen_icon']['label'];

        $html_submitButton_value = $response['submit_button']['value'];

        // echo '<div class="allergens_form"><form action="" method="post" enctype="multipart/form-data" class="add_allergens_form">';
        // echo '</form></div>';

        $content_class = str_replace('_', '-', strtolower(__CLASS__));

        $html = <<<HTML
        <form action="" method="post" enctype="multipart/form-data" class="$content_class">
            <fieldset>
                <div>
                    <label for="{$html_allergenName_id}">{$html_allergenName_label}</label>
                    <input type="text" name="{$html_allergenName_id}" id="{$html_allergenName_id}" value="{$html_allergenName_value}"/>
                </div>

                <div>
                    <label for="{$html_allergenDescription_id}">{$html_allergenDescription_label}</label>
                    <input type="text" name="{$html_allergenDescription_id}" id="{$html_allergenDescription_id}" value="{$html_allergenDescription_value}"/>
                </div>

                <div>
                    <label for="{$html_allergenIcon_id}">{$html_allergenIcon_label}</label>
                    <input type="file" name="{$html_allergenIcon_id}" id="{$html_allergenIcon_id}"/>
                </div>

                <div>
                    <input type="submit" name="submit" class="button button-primary" value="{$html_submitButton_value}" />
                </div>
            </fieldset>
        </form>
        HTML;

        echo $html;
    }

}
