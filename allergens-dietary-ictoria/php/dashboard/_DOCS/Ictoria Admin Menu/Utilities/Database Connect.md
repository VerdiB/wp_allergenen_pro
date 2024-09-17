# Database Connect

This file is the main class for interaction with the `allergen.php`, `allergen_attachment.php` and `attachment.php` queries inside the `DB` directory.

These are just crude basic functions at the moment, these need to be expanded upon, but the framework is set to do this.

## Code Explanation

Most of these functions take a `$request` argument, this is from the [REST API](Rest%20Routes.md). We use it to extract the parameters, data etc.

It is important to import the `DB` files as they are used here to interact with the database.

<details>
  <summary>Click to show code (class IAM_Database_Connect):</summary>

```php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/* DB/allergen.php */
if (!class_exists('Allergens_Dietary_Ictoria_Allergen_Queries')) {
  require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
}

/* DB/allergen_attachment.php */
if (!class_exists('Allergens_Dietary_Ictoria_Allergy_Attachment_Queries')) {
  require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

/* DB/attachment.php */
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
```

</details>

### get_allergen_by_name($request)

Takes the `?name=` parameter from the url via `$request` and uses it to use the query function `allergen.php->getAllergen()` to retrieve a `$result`

```JSON
[
    {
        "allergy_name": "Soya",
        "allergy_description": "Soya description text",
        "allergy_activated": "1"
    }
]
```

<details>
  <summary>Click to show code (function get_allergen_by_name($request)):</summary>

```php
    public static function get_allergen_by_name($request)
    {
        $allergenName = $request->get_param('name');
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();
        return $allergen_queries->getAllergen($allergenName);
    }
```

</details>

### get_allergens_with_attachments($request)

This uses multiple queries to retrieve the `name` and `icon_url` from the database. This should be split and expanded into separate functions, this is just to make things work for now.

<details>
  <summary>Click to show code ($response):</summary>

```JSON
{
    "alcohol": {
        "allergen_name": "Alcohol",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_alcohol.png"
    },
    "celery": {
        "allergen_name": "Celery",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_celery.png"
    },
    "corn": {
        "allergen_name": "Corn",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_corn.png"
    },
    "crustaceans": {
        "allergen_name": "Crustaceans",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_crustaceans.png"
    },
    "dairy": {
        "allergen_name": "Dairy",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_dairy.png"
    },
    "eggs": {
        "allergen_name": "Eggs",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_eggs.png"
    },
    "fish": {
        "allergen_name": "Fish",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_fish.png"
    },
    "gluten": {
        "allergen_name": "Gluten",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_gluten.png"
    },
    "lupin": {
        "allergen_name": "Lupin",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_lupin.png"
    },
    "molluscs": {
        "allergen_name": "Molluscs",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_molluscs.png"
    },
    "mustard": {
        "allergen_name": "Mustard",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_mustard.png"
    },
    "nuts": {
        "allergen_name": "Nuts",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_nuts.png"
    },
    "peanuts": {
        "allergen_name": "Peanuts",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_peanuts.png"
    },
    "sesame": {
        "allergen_name": "Sesame",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_sesame.png"
    },
    "soya": {
        "allergen_name": "Soya",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_soya.png"
    },
    "sulfite": {
        "allergen_name": "Sulfite",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_sulfite.png"
    },
    "test": {
        "allergen_name": "test",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_gluten.png"
    },
    "wheat": {
        "allergen_name": "Wheat",
        "icon_url": "http://localhost:8080/wp-content/plugins/allergens-dietary-ictoria/assets/icons/allergens_wheat.png"
    }
}
```

</details>

<details>
  <summary>Click to show code (function get_allergens_with_attachments()):</summary>

```php
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
```

</details>

### add_allergen($request)

Takes the `?name=` parameter from the url via `$request` and uses it to use the query function `allergen.php->addAllergens()`. This only adds `name` so it needs to be expanded on like the other functions, these are all placeholders to make things work for now.

```JSON
"Allergen added successfully" : "Failed to add allergen"
```

<details>
  <summary>Click to show code (function add_allergen($request)):</summary>

```php
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
```

</details>

### update_allergen($request)

Takes the `?name=` parameter from the url via `$request` and uses it to use the query function `allergen.php->updateAllergens()`. This only adds `name` so it needs to be expanded on like the other functions, these are all placeholders to make things work for now.

```JSON
"Allergen not found" : "Allergen updated successfully"
```

<details>
  <summary>Click to show code (function update_allergen($request)):</summary>

```php
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
```

</details>

### delete_allergen($request)

Takes the `?name=` parameter from the url via `$request` and uses it to use the query function `allergen.php->updateAllergens()`. This only adds `name` so it needs to be expanded on like the other functions, these are all placeholders to make things work for now.

This one especially needs to be checked.

```JSON
"Allergen not found" : "Allergen updated successfully"
```

<details>
  <summary>Click to show code (function delete_allergen($request)):</summary>

```php
    public static function delete_allergen($request)
    {
        $data = $request->get_json_params();
        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();

        if (!$allergen_queries->checkAllergenExists($data['allergen_name'])) {
            return new WP_REST_Response("Allergen not found", 404);
        }

        $allergen_queries->updateAllergens($data);
        return new WP_REST_Response("Allergen updated successfully", 200);
    }
```

</details>

### toggle_allergen_activation($request)

Takes the `?name=` and `&activate=` parameter, it checks if activate is a boolean and then processes it to return something.

This one especially needs to be checked.

```JSON
"Allergen not found" : "Allergen activation toggled successfully" : "Failed to toggle allergen activation"
```

<details>
  <summary>Click to show code (function toggle_allergen_activation($request)):</summary>

```php
    public static function toggle_allergen_activation($request)
    {
        $allergenName = $request->get_param('name');
        $activate = filter_var($request->get_param('activate'), FILTER_VALIDATE_BOOLEAN);

        $allergen_queries = Allergens_Dietary_Ictoria_Allergen_Queries::getInstance();

        if (!$allergen_queries->checkAllergenExists($allergenName)) {
            return new WP_REST_Response("Allergen not found", 404);
        }

        $success = $allergen_queries->toggleAllergenActivation($allergenName, $activate);
        return new WP_REST_Response($success ? "Allergen activation toggled successfully" : "Failed to toggle allergen activation", $success ? 200 : 500);
    }
}
```

</details>
