<?php
if (!defined('ABSPATH')) {
    exit;
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

    // public static function insert_allergen($name, $description)
    // {
    //     global $wpdb;

    //     dbDelta(
    //         $wpdb->query(
    //             $wpdb->prepare(
    //                 "INSERT INTO {$wpdb->prefix}allergens_dietary_ictoria_allergy (allergy_name, allergy_description) VALUES (%s, %s)",
    //                 array(
    //                     $name,
    //                     $description,
    //                 )
    //             )
    //         )
    //     );
    // }

    public function addallergens()
    {
        if (!class_exists('Allergens_Dietary_Ictoria_Form')) {
            require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
        }
        Allergens_Dietary_Ictoria_Form::setFormType(FormType::ALLERGENS);
        Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
    }

    // public static function populate_allergen_table()
    // {
    //     $options = self::get_local_options();

    //     foreach ($options as $allergen => $value) {
    //         $title = $value['title'];
    //         $description = $title . ' description text';

    //         $category = $value['category'];
    //         if ($category == 'allergen') {
    //             self::insert_allergen($title, $description);
    //         }

    //     }
    // }

    // public static function get_allergens()
    // {
    //     global $wpdb;

    //     $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}allergens_dietary_ictoria_allergy");
    //     $allergens = $wpdb->get_results($sql);
    //     return $allergens;
    // }
}
IAM_Database_Connect::instance();
