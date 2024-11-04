<?php

// exit if user can access this file directly
if (!defined('ABSPATH')) {
    exit;
}

if (!interface_exists('I_Allergens_Dietary_Ictoria_Form')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/Iallergen_form.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Attachment_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
}
if (!enum_exists('Mime_Types')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/lists/mime_types.php';
}

/**
 * @brief This shows the tabs on add/update allergens .
 * @author T.K.
 * @since 1.0.0
 * @date 18-9-2024
 */

// if ( ! class_exists( 'Allergens_Dietary_Ictoria_Tabs' ) ) {
// 	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
// }

/********************************************************************/

class Allergens_Dietary_Ictoria_Update_Allergen_Form implements I_Allergens_Dietary_Ictoria_Form
{
    private array $MIME_TYPES;

    public function __construct()
    {
        $this->MIME_TYPES = Mime_Types::get_mime_types();
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

        global $wpdb;
        $table_name_allergy = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $table_name_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
        $table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

        $html = '';

        $allergens = $wpdb->get_results(
            "SELECT a.allergy_name, a.allergy_description, aa.attachment_name, att.attachment_path
					FROM $table_name_allergy_attachment AS aa
					JOIN $table_name_allergy AS a ON aa.allergy_name = a.allergy_name
                    JOIN $table_name_attachment AS att ON aa.attachment_name = att.attachment_name"
        );

        $html .= '<h1>' . __('Update Allergen Icons', 'allergens-dietary-ictoria') . '</h1>';
        $html .= '<table>';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>' . __('Allergen', 'allergens-dietary-ictoria') . '</th>';
        $html .= '<th>' . __('Description', 'allergens-dietary-ictoria') . '</th>';
        $html .= '<th>' . __('Current Icon', 'allergens-dietary-ictoria') . '</th>';
        $html .= '<th>' . __('Upload New Icon', 'allergens-dietary-ictoria') . '</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($allergens as $allergen) {
            $html .= '<tr>';
            $html .= '<td>' . esc_html($allergen->allergy_name) . '</td>';
            $html .= '<td>' . esc_html($allergen->allergy_description) . '</td>';
            $html .= '<td>' . '<img style="width:50px;" src="' . esc_html($allergen->attachment_path) . '" alt="' . esc_html($allergen->attachment_name) . '" </td>';
            // $html .= '';
            $html .= '<td>';
            $html .= '<input type="hidden" name="allergen_icon_hidden[' . esc_attr($allergen->allergy_name) . ']" value="' . ($allergen->attachment_name ? esc_attr($allergen->attachment_name) : "") . '" >';
            $html .= '<input type="file" name="allergen_icon[' . esc_attr($allergen->allergy_name) . ']" id="allergen_icon_' . esc_attr($allergen->allergy_name) . '" >';
            $html .= '</td>';
            // $html .= '';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<input type="submit" name="submit" value="' . __('Update Icons', 'allergens-dietary-ictoria') . '" >';

        echo $html;
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

        if (empty($data['allergen_icon']['name']) || empty($data['allergen_icon_hidden'])) {
            return;
        }

        Allergens_Dietary_Ictoria_Attachment_Queries::getInstance()->update_allergen_icons($data, $this->MIME_TYPES);
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

        foreach ($data['allergen_icon_hidden'] as $key => $value) {
            $data['allergen_icon_hidden'][$key] = sanitize_file_name($value);
        }

        if (isset($data['allergen_icon']) && is_array($data['allergen_icon'])) {
            foreach ($data['allergen_icon'] as $key => $fileData) {
                if (is_array($fileData) && isset($fileData['name'])) {
                    $data['allergen_icon'][$key]['name'] = sanitize_file_name($fileData['name']);
                }
            }
        }

        return $data;
    }
}
