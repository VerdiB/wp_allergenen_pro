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
        }
    }

    // Get a specific allergen by name
    public static function get_allergen_by_name($request)
    {
        $allergenName = $request->get_param('name');
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();
        return $allergen_queries->getAllergen($allergenName);
    }

    // Get all allergens with their respective attachments
    public static function get_allergens_with_attachments()
    {
        global $wpdb;

        $sql_allergens = "SELECT * FROM {$wpdb->prefix}allergens_dietary_ictoria_allergy";
        $allergens = $wpdb->get_results($sql_allergens);

        $response = [];
        foreach ($allergens as $allergen) {
            // Get the attachment for each allergen
            $sql_attachment = $wpdb->prepare(
                "SELECT attachment_name FROM {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment WHERE allergy_name = %s",
                $allergen->allergy_name
            );
            $attachment_result = $wpdb->get_row($sql_attachment);

            if ($attachment_result) {
                $sql_attachment_path = $wpdb->prepare(
                    "SELECT attachment_path FROM {$wpdb->prefix}allergens_dietary_ictoria_attachments WHERE attachment_name = %s",
                    $attachment_result->attachment_name
                );
                $attachment_path_result = $wpdb->get_row($sql_attachment_path);

                if ($attachment_path_result) {
                    $icon_url = plugins_url('assets/icons/' . basename($attachment_path_result->attachment_path), ALLERGENS_DIETARY_ICTORIA_BASE);
                    $response[strtolower($allergen->allergy_name)] = [
                        'allergen_name' => $allergen->allergy_name,
                        'icon_url' => $icon_url,
                    ];
                }
            }
        }
        return $response;
    }

    // Add a new allergen
    public static function add_allergen($request)
    {
        $data = $request->get_json_params();
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();

        if ($allergen_queries->checkAllergenExists($data['allergen_name'])) {
            return new WP_REST_Response("Allergen already exists", 400);
        }

        $success = $allergen_queries->addAllergens($data);
        return new WP_REST_Response($success ? "Allergen added successfully" : "Failed to add allergen", $success ? 200 : 500);
    }

    // Update an existing allergen
    public static function update_allergen($request)
    {
        $data = $request->get_json_params();
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();

        if (!$allergen_queries->checkAllergenExists($data['allergen_name'])) {
            return new WP_REST_Response("Allergen not found", 404);
        }

        $allergen_queries->updateAllergens($data);
        return new WP_REST_Response("Allergen updated successfully", 200);
    }

    // Delete an allergen by name
    public static function delete_allergen($request)
    {
        $allergenName = $request->get_param('name');
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();

        if (!$allergen_queries->checkAllergenExists($allergenName)) {
            return new WP_REST_Response("Allergen not found", 404);
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $wpdb->delete($table_name, array('allergy_name' => $allergenName));

        return new WP_REST_Response("Allergen deleted successfully", 200);
    }

    // Toggle allergen activation status
    public static function toggle_allergen_activation($request)
    {
        $allergenName = $request->get_param('name');
        $activate = $request->get_param('activate');
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();

        if (!$allergen_queries->checkAllergenExists($allergenName)) {
            return new WP_REST_Response("Allergen not found", 404);
        }

        $success = $allergen_queries->toggleAllergenActivation($allergenName, $activate);
        return new WP_REST_Response($success ? "Allergen activation toggled successfully" : "Failed to toggle allergen activation", $success ? 200 : 500);
    }
}
