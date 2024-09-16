<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class IAM_Rest_Routes
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new IAM_Rest_Routes();
        }
    }

    public static function register_iam_rest_routes()
    {
        // Get a specific allergen by name
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/get-allergen',
            array(
                'methods' => 'GET',
                'callback' => array('IAM_Database_Connect', 'get_allergen_by_name'),
                'args' => [
                    'name' => [
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return is_string($param);
                        },
                    ],
                ],
            )
        );

        // Get all allergens with their respective attachments
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/allergens-with-attachments',
            array(
                'methods' => 'GET',
                'callback' => array('IAM_Database_Connect', 'get_allergens_with_attachments'),
            )
        );

        // Add a new allergen
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/add-allergen',
            array(
                'methods' => 'POST',
                'callback' => array('IAM_Database_Connect', 'add_allergen'),
                'permission_callback' => '__return_true',
            )
        );

        // Update an existing allergen
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/update-allergen',
            array(
                'methods' => 'POST',
                'callback' => array('IAM_Database_Connect', 'update_allergen'),
                'permission_callback' => '__return_true',
            )
        );

        // Delete an allergen
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/delete-allergen',
            array(
                'methods' => 'DELETE',
                'callback' => array('IAM_Database_Connect', 'delete_allergen'),
                'args' => [
                    'name' => [
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return is_string($param);
                        },
                    ],
                ],
                'permission_callback' => '__return_true',
            )
        );

        // Toggle allergen activation
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/toggle-activation',
            array(
                'methods' => 'POST',
                'callback' => array('IAM_Database_Connect', 'toggle_allergen_activation'),
                'args' => [
                    'name' => [
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return is_string($param);
                        },
                    ],
                    'activate' => [
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return is_string($param) && in_array(strtolower($param), ['true', 'false', '1', '0']);
                        },
                    ],
                ],
                'permission_callback' => '__return_true',
            )
        );
    }
}
