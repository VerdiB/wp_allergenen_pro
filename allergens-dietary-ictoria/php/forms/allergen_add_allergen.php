<?php

// exit if user can access this file directly
if (!defined('ABSPATH')) {
    exit;
}

if (!interface_exists('I_Allergens_Dietary_Ictoria_Form')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/Iallergen_form.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Attachment_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

class Allergens_Dietary_Ictoria_Allergen_Form implements I_Allergens_Dietary_Ictoria_Form
{

    private ?array $_allergen = null;
    private const MIME_TYPES = array('image/png', 'image/jpeg', 'image/jpg');

    public function __construct()
    {
    }

    /**
     * @param string|null $allergenName
     * @brief This method shows the form to add/update allergens .
     * @return void
     * @author V.B.
     * @since 1.0.0
     * @date 11-9-2024
     */
    public function showForm(?string $allergenName = null)
    {
        // if (!is_null($allergenName)) {
        //     // TODO: Implement showForm() method. when the allergen name is not null
        //     if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
        //         require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
        //     }
        //     $this->_allergen = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->getAllergyAttachment($allergenName);
        //     return;
        // }

        if (!is_null($allergenName)) {
            // When allergenName is provided, fetch its data
            $this->_allergen = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->getAllergyAttachment($allergenName);
            return;
        }

        // Field: Allergen Name
        $html_allergenName = [
            'id' => 'allergen_name',
            'label' => __('Allergen name', 'allergens-dietary-ictoria'),
            'value' => (!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '',
        ];

        // Field: Allergen Description
        $html_allergenDescription = [
            'id' => 'allergen_description',
            'label' => __('Allergen description', 'allergens-dietary-ictoria'),
            'value' => (!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '',
        ];

        // Field: Allergen Icon
        $html_allergenIcon = [
            'id' => 'allergen_icon',
            'label' => __('Allergen icon', 'allergens-dietary-ictoria'),
        ];

        // Submit Button
        $html_submitButton = [
            'id' => 'submit_button',
            'label' => __('Submit', 'allergens-dietary-ictoria'),
            'value' => __('Add allergen', 'allergens-dietary-ictoria'),
        ];

        // Combine into a response array
        $response = [
            'fields' => [
                'allergen_name' => $html_allergenName,
                'allergen_description' => $html_allergenDescription,
                'allergen_icon' => $html_allergenIcon,
            ],
            'submit_button' => $html_submitButton,
        ];

        // Return the data (instead of rendering HTML directly)
        return $response;

        /** */
        // $html_allergenName_id = 'allergen_name';
        // $html_allergenName_label = __('Allergen name', 'allergens-dietary-ictoria');
        // $html_allergenName_value = ((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '');

        // $html_allergenDescription_id = 'allergen_description';
        // $html_allergenDescription_label = __('Allergen description', 'allergens-dietary-ictoria');
        // $html_allergenDescription_value = ((!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '');

        // $html_allergenIcon_id = 'allergen_icon';
        // $html_allergenIcon_label = __('Allergen icon', 'allergens-dietary-ictoria');

        // $html_submitButton_value = __('Add allergen', 'allergens-dietary-ictoria');

        // $response = [];

        // $response['html_allergenName'] = ['id' => $html_allergenName_id, 'label' => $html_allergenName_label, 'value' => $html_allergenName_value];
        // $response['html_allergenDescription'] = ['id' => $html_allergenDescription_id, 'label' => $html_allergenDescription_label, 'value' => $html_allergenDescription_value];
        // $response['html_allergenIcon'] = ['id' => $html_allergenIcon_id, 'label' => $html_allergenIcon_label];
        // $response['html_submitButton'] = ['value' => $html_submitButton_value];

        // var_dump($response);
        // return $response;
        /** */
        // $html = <<<HTML
        //         <fieldset>
        //             <div>
        //                 <label for="{$html_allergenName_id}">{$html_allergenName_label}</label>
        //                 <input type="text" name="{$html_allergenName_id}" id="{$html_allergenName_id}" value="{$html_allergenName_value}"/>
        //             </div>

        //             <div>
        //                 <label for="{$html_allergenDescription_id}">{$html_allergenDescription_label}</label>
        //                 <input type="text" name="{$html_allergenDescription_id}" id="{$html_allergenDescription_id}" value="{$html_allergenDescription_value}"/>
        //             </div>

        //             <div>
        //                 <label for="{$html_allergenIcon_id}">{$html_allergenIcon_label}</label>
        //                 <input type="file" name="{$html_allergenIcon_id}" id="{$html_allergenIcon_id}"/>
        //             </div>

        //             <div>
        //                 <input type="submit" name="submit" class="button button-primary" value="{$html_submitButton_value}" />
        //             </div>
        //         </fieldset>
        //         HTML;

        // echo $html;
    }

    /**
     * @param array $data
     * @brief This method submits the form data to the DB.
     * @throws Exception if the file is not a valid image
     * @return void
     * @author V.B.
     * @since 1.0.0
     * @date 11-9-2024
     */
    public function submit(array $data)
    {
        $data = $this->sanitize($data);

        if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
            require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
        }

        (false === Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->checkAllergenExists($data['allergen_name'])) ?
        Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->addAllergens($data) :
        Allergens_Dietary_Ictoria_Allergen_Queries::getInstance()->updateAllergens($data);

        if (false === wp_check_filetype($data['allergen_icon']['name'], self::MIME_TYPES)) {
            throw new Exception(__('The file is not a valid image'));
            return;
        } else {

            (false === Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->checkAttachmentExists($data['allergen_icon']['name'])) ?
            Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->addAttachment($data['allergen_icon']) :
            Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->updateAttachment($data['allergen_icon']);
        }

        (false === Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->checkAllergyAttachmentExists($data['allergen_name'])) ?
        Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->addAllergyAttachment($data) :
        Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance()->updateAllergyAttachment($data);
    }

    /**
     * @param array $data
     * @brief This method sanitizes the form data for the DB.
     * @return array $data
     * @author V.B.
     * @since 1.0.0
     * @date 11-9-2024
     */
    public function sanitize(array $data)
    {
        $data['allergen_name'] = sanitize_text_field(wp_unslash($data['allergen_name']));
        $data['allergen_description'] = sanitize_text_field(wp_unslash($data['allergen_description']));
        $data['allergen_icon']['name'] = sanitize_file_name($data['allergen_icon']['name']);

        return $data;
    }
}
