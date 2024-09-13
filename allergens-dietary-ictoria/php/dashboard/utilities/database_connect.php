<?php
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Database_Connect
{
    public static function get_local_options()
    {
        $options = Allergens_Dietary_Ictoria_Functions::default_options();
        return $options;
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
}
