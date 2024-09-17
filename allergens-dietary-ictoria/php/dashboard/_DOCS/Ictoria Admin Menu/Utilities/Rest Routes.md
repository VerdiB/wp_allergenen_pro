# Rest Routes

The rest routes use the WordPress [REST API](https://developer.wordpress.org/rest-api/), the Ictoria Admin Dashboard uses the `iam/v2` namespace. You need to make sure the REST API is enabled/setup, changing the `permalinks` setting in the WordPress dashboard `settings` to post and saving it will make sure it is enabled. If set up correctly the WordPress base REST API should be available on [http://localhost:8080/wp-json/](http://localhost:8080/wp-json/).

## Viewing Registered Routes

As we are using the `iam/v2` namespace, all routes registered are able to be seen by going to [http://localhost:8080/wp-json/iam/v2/](http://localhost:8080/wp-json/iam/v2/), routes can also be called like this e.g. [http://localhost:8080/wp-json/iam/v2/allergens-dietary/get-allergen?name=soya](http://localhost:8080/wp-json/iam/v2/allergens-dietary/get-allergen?name=soya) (if it exists in the DB), but this can only be done with `GET` routes, the others need something like PostMan/Insomnia or a vscode extension like REST API Client.

## Code Explanation

The start is self explanatory, I've used an abstract class for possible future implementation of expanding upon the rest routes by each page if necessary. This follows the mindset of keeping things together that are related and using bases/prototypes to expand on and/or use _if needed_.

Also note we are using `allergens-dietary/` at the start of each route because it is related to the allergens-dietary page. You could nest deeper but this should not be done manually, that is something that should be done if/when the routes modularization per page exists and be done with an autoloader in a base class for routes or something.

<details>
  <summary>Click to show code (class IAM_Rest_Routes):</summary>

```php
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
```

</details>

### register_iam_rest_routes()

This function is the callback that is usedto register the routes in the [Main Entry File](../Main%20Entry%20File/README.md) `__construct()`. It's contents will be split per [register_rest_route()](https://developer.wordpress.org/reference/functions/register_rest_route/).

#### Get Allergen By Name

<details>
  <summary>Click to show code (function register_rest_route()):</summary>

```php
    public static function register_iam_rest_routes()
    {
        /* Get a specific allergen by name.
         * e.g.
         * http://localhost:8080/wp-json/iam/v2/allergens-dietary/get-allergen?name=value
         */
        register_rest_route(
            'iam/v2', /* $route_namespace */
            '/allergens-dietary/get-allergen', /* $route */
            array(
                /* request method */
                'methods' => 'GET',
                /* callback function:
                 * iam-database-connect.php->get_allergen_by_name() */
                'callback' => array('IAM_Database_Connect', 'get_allergen_by_name'),
                /* $args
                 * define parameter `name=` */
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
```

</details>

#### Get Allergens With Attachments

<details>
  <summary>Click to show code (function register_rest_route()):</summary>

```php
        /* Get all allergens with attachments (icons).
         * e.g.
         * http://localhost:8080/wp-json/iam/v2/allergens-dietary/allergens-with-attachments
         */
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/allergens-with-attachments',
            array(
                'methods' => 'GET',
                'callback' => array('IAM_Database_Connect', 'get_allergens_with_attachments'),
            )
        );
```

</details>

#### Add New Allergen

<details>
  <summary>Click to show code (function register_rest_route()):</summary>

```php
        /* Add allergen, for now only adds the name.
         * I am still thinking what would be best, multiple route calls,
         * adding more parameters or a separate function.
         *
         * POST/DELETE methods use the permission_callback option
         * This just returns true but is in place for proper
         * permissions check functions that include other private
         * database related checks.
         *
         * e.g.
         * http://localhost:8080/wp-json/iam/v2/allergens-dietary/add-allergen?name=value
         */
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/add-allergen',
            array(
                'methods' => 'POST',
                'callback' => array('IAM_Database_Connect', 'add_allergen'),
                'permission_callback' => '__return_true',
            )
        );
```

</details>

#### Update Allergen

<details>
  <summary>Click to show code (function register_rest_route()):</summary>

```php
        /* Update allergen, for now only updates the name.
         * e.g.
         * http://localhost:8080/wp-json/iam/v2/allergens-dietary/update-allergen?name=value
         */
        register_rest_route(
            'iam/v2',
            '/allergens-dietary/update-allergen',
            array(
                'methods' => 'POST',
                'callback' => array('IAM_Database_Connect', 'update_allergen'),
                'permission_callback' => '__return_true',
            )
        );
```

</details>

#### Delete Allergen

<details>
  <summary>Click to show code (function register_rest_route()):</summary>

```php
        /* Delete allergen, for now only deletes allergy table entry.
         * e.g.
         * http://localhost:8080/wp-json/iam/v2/allergens-dietary/delete-allergen?name=value
         */
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
```

</details>

#### Toggle Allergen Activation

<details>
  <summary>Click to show code (function register_rest_route()):</summary>

```php
        /* Toggle allergen activated property.
         * Activate only takes a bool as string:
         *    ['1', '0', 'true', 'false']
         * e.g.
         * http://localhost:8080/wp-json/iam/v2/allergens-dietary/delete-allergen?name=value&activate=false
         */
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
```

</details>
