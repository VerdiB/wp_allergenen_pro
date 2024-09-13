<?php
if (!defined('ABSPATH')) {
    exit;
}

// Include the files for the required classes
if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php'; // Add this line
}

if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

if (!class_exists('Allergens_Dietary_Ictoria_Attachment_Queries')) {
    require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
}

class IAM_Database_Connect
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new IAM_Database_Connect();

            // self::populate_allergen_table();
        }
    }

    public static function get_local_options()
    {
        return Allergens_Dietary_Ictoria_Functions::default_options();
    }

    public static function insert_allergen($name, $description)
    {
        global $wpdb;

        dbDelta(
            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO {$wpdb->prefix}allergens_dietary_ictoria_allergy (allergy_name, allergy_description) VALUES (%s, %s)",
                    array(
                        $name,
                        $description,
                    )
                )
            )
        );
    }

    public static function populate_allergen_table()
    {

        // Get the allergen and attachment queries instances
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();
        $attachment_queries = Allergens_Dietary_Ictoria_Attachment_Queries::getInstance();
        $allergy_attachment_queries = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance();

        // Fetch all options
        $options = self::get_local_options();

        foreach ($options as $key => $value) {
            $allergen_name = $value['title'];
            $description = $allergen_name . ' description text';
            $icon_path = $value['icon'];

            // Step 1: Check if allergen exists, if not, add it
            if (!$allergen_queries->checkAllergenExists($allergen_name)) {
                $allergen_data = array(
                    'allergen_name' => $allergen_name,
                    'allergen_description' => $description,
                );
                $allergen_queries->addAllergens($allergen_data);
            }

            // Step 2: Handle the attachment (icon)
            $attachment_data = array(
                'name' => basename($icon_path),
                'full_path' => basename($icon_path),
            );

            // Step 3: Check if the attachment exists in the attachments table, if not, add it
            if (!$attachment_queries->checkAttachmentExists($attachment_data['name'])) {
                $attachment_queries->addAttachment($attachment_data);
            }

            // Step 4: Link the allergen with the attachment in the allergy_attachment table
            if (!$allergy_attachment_queries->checkAllergyAttachmentExists($allergen_name)) {
                $allergy_attachment_queries->addallergyAttachment(array(
                    'allergen_name' => $allergen_name,
                    'allergen_icon' => array('name' => $attachment_data['name']),
                ));
            }
        }
    }

    public static function get_allergens_with_attachments()
    {
        global $wpdb;

        // Query to get all allergens
        $sql_allergens = "SELECT * FROM {$wpdb->prefix}allergens_dietary_ictoria_allergy";
        $allergens = $wpdb->get_results($sql_allergens);

        $response = [];

        foreach ($allergens as $allergen) {
            // Get the attachment name from the allergy_attachment table using the allergy name
            $sql_attachment = $wpdb->prepare(
                "SELECT attachment_name FROM {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment WHERE allergy_name = %s",
                $allergen->allergy_name
            );
            $attachment_result = $wpdb->get_row($sql_attachment);

            if ($attachment_result) {
                // Get the attachment path from the attachments table using the attachment name
                $sql_attachment_path = $wpdb->prepare(
                    "SELECT attachment_path FROM {$wpdb->prefix}allergens_dietary_ictoria_attachments WHERE attachment_name = %s",
                    $attachment_result->attachment_name
                );
                $attachment_path_result = $wpdb->get_row($sql_attachment_path);

                // Output or process the allergen name and the attachment path
                if ($attachment_path_result) {
                    // Use plugins_url() to get the correct URL for the icon
                    $icon_url = plugins_url('assets/icons/' . basename($attachment_path_result->attachment_path), ALLERGENS_DIETARY_ICTORIA_BASE);

                    // echo '<img src="' . esc_url($icon_url) . '" alt="Allergen Icon"><br>';
                    // echo $allergen->allergy_name . '<br><br>';

                    $response[strtolower($allergen->allergy_name)] = ['allergen_name' => $allergen->allergy_name, 'icon_url' => $icon_url];
                }
            }
        }
        return $response;
    }
}

IAM_Database_Connect::instance();
