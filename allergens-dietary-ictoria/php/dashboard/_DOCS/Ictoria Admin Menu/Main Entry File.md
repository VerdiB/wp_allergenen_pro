# Main Entry File

The main entry file for the dashboard is [ictoria-admin-menu.php](../../ictoria-admin-menu.php). This file loads in all the pages, utilities and its corrsponding assets.

Besides [ictoria-admin-menu.php](../../ictoria-admin-menu.php) there also is [ictoria-admin-menu.css](../../ictoria-admin-menu.css) and [ictoria-admin-menu.js](../../ictoria-admin-menu.js), these files get enqueued in the `iam_style()` & `iam_script()` functions.

## Setup & Autoloader

Each page had its own directory `page_title`, entry files `iam-page-title.php` and sections `sections/iam-page-title-section-title.php`, it is important to follow the naming & file structure for everything to load automatically.

- **$directories** <br>- The directories array holds the paths of directories inside `dashboard` that need to be checked by the autoloader, **it is important to add onto this if you make a new page**.
- **$autoload_styles & $autoload_scripts** <br>- These arrays are populated during the main `autoload()` to hold the scripts and styles for each page so they can be enqueued.

## Code explanation

The start is covered above.

<details>
  <summary>Click to show code (class Ictoria_Admin_Menu):</summary>

```php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('IAM_DIR', **DIR**);

class Ictoria_Admin_Menu
{
    private static $_instance = null;

    /* The directories for autoloader
     * ! It is important to add each new page directory.
     */
    private static $directories = [
        IAM_DIR . '/utilities/',
        IAM_DIR . '/utilities/base_classes/',
        IAM_DIR . '/page_ictoria-dashboard/',
        IAM_DIR . '/page_allergens-dietary/',
        IAM_DIR . '/page_settings/',
    ];

    /* The variables are also for the autoloader but for
     * a different use. These arrays will be looped through
     * in the `iam_style()` & `iam_script()` functions later on.
     */
    private static $autoload_styles = [];
    private static $autoload_scripts = [];

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
```

</details>

### \_\_construct()

During [`Ictoria_Admin_Menu`](../../ictoria-admin-menu.php)`::__construct()` the actions registering the [IAM_Rest_Routes](../../utilities/iam-rest-routes.php) and for enqueue'ing the main css/js files, but also each of the pages' css/jss that got added to `$autoload_styles` & `$autoload_scripts` during the [`Ictoria_Admin_Menu`](../../ictoria-admin-menu.php)`::autoload()`.

It is important to instantiate each page here as well. <sup>_I am still working on creating a function for doing this automatically._</sup>

<details>
  <summary>Click to show code (function __construct()):</summary>

```php
    private function __construct()
    {

        /* Register the rest API routes.
         */
        add_action('rest_api_init', ['IAM_Rest_Routes', 'register_iam_rest_routes']);

        /* Add the `iam_style()` & `iam_script()` functions to its queue.
         */
        add_action('admin_enqueue_scripts', [__CLASS__, 'iam_style']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'iam_script']);

        /* Instantiation of pages.
         * ! It is important to instantiate each new page.
         */
        IAM_Page_Ictoria_Dashboard::instance();
        IAM_Page_Allergens_Dietary::instance();
        IAM_Page_Settings::instance();
    }
```

</details>

### iam_style()

In [`Ictoria_Admin_Menu`](../../ictoria-admin-menu.php)`::iam_style()` the adding of css to the WordPress queue happens, the _foreach_ loop goes through the items added to `$autoload_styles` and adds them to the queue as well.

The [variables.css](../../assets/css/variables.css) <sup>[[\*1]](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)</sup> is where the css variables that are to be used globally reside. e.g. `--iam-primary-background-color: red;` which can then be referenced in other css files like this `background-color: var(--iam-primary-background-color);`.

<details>
  <summary>Click to show code (function iam_style()):</summary>

```php
    public static function iam_style()
    {
        /* Load css variables & base css.
         */
        wp_enqueue_style(
            'variables-css',
            plugins_url('assets/css/variables.css', IAM_DIR)
        );
        wp_enqueue_style(
            'ictoria-admin-menu-css',
            plugins_url('dashboard/ictoria-admin-menu.css', IAM_DIR)
        );

        /* Loop through `$autoload_styles` to enqueue each pages'
         * base css file.
         */
        foreach (self::$autoload_styles as $handle => $style) {
            wp_enqueue_style($handle, plugins_url($style[0], IAM_DIR), $style[1], $style[2]);
        }
    }
```

</details>

### iam_script()

This speaks for itself, as the above, it enqueues but this time the js files.

<details>
  <summary>Click to show code (function iam_script()):</summary>

```php
    public static function iam_script()
    {
        /* Load main js script.
         */
        wp_enqueue_script(
            'iam-js',
            plugins_url('dashboard/ictoria-admin-menu.js', IAM_DIR),
            ['jquery'],
            false,
            true
        );

        /* Loop through `$autoload_scripts` to enqueue each pages'
         * base script file.
         */
        foreach (self::$autoload_scripts as $handle => $script) {
            wp_enqueue_script($handle, plugins_url($script[0], IAM_DIR), $script[1], $script[2], $script[3]);
        }
    }
```

</details>

### autoload($class_name)

The autoload function is used by [spl_autoload_register](https://www.php.net/manual/en/function.spl-autoload-register.php) to load the pages. It takes $class_name as a argument to convert to the [file naming structure](./README.md).

<details>
  <summary>Click to show code (function autoload($class_name)):</summary>

```php
    public static function autoload($class_name)
    {
        /* Store formatted filenames e.g.:
         * $name          = iam-page-ictoria-dashboard
         * $file_name     = iam-page-ictoria-dashboard.php
         * $file_name_css = iam-page-ictoria-dashboard.css
         * $file_name_js  = iam-page-ictoria-dashboard.js
         */
        $name = str_replace('_', '-', strtolower($class_name));
        $file_name = $name . '.php';
        $file_name_css = $name . '.css';
        $file_name_js = $name . '.js';

        /* Check if class starts with `IAM_`. */
        if (strpos($class_name, 'IAM_') === 0) {

            /* Loop through `$directories` with `$directory` as
             * e.g. 'route/to/path/page_ictoria-dashboard/'
             * or   'route/to/path/utilities/'
             */
            foreach (self::$directories as $directory) {

                /* If file exists, require it. */
                if (file_exists($directory . $file_name)) {
                    require_once $directory . $file_name;

                    /* Check if directory starts with `page_`. */
                    if (strpos(basename($directory), 'page_') === 0) {
                        /* Check and add CSS file to $autoload_styles. */
                        if (file_exists($directory . $file_name_css)) {
                            self::$autoload_styles[$name . '-css'] = [
                                'dashboard/page_' . str_replace('iam-page-', '', $name) . '/' . $file_name_css,
                                [],
                                'all',
                            ];
                        }

                        /* Check and add JS file to $autoload_scripts. */
                        if (file_exists($directory . $file_name_js)) {
                            self::$autoload_scripts[$name . '-js'] = [
                                'dashboard/page_' . str_replace('iam-page-', '', $name) . '/' . $file_name_js,
                                ['jquery'],
                                false,
                                true,
                            ];
                        }
                    }

                    return;
                }
            }
        }
    }
}

/* Autoload classes. */
spl_autoload_register(['Ictoria_Admin_Menu', 'autoload']);

/* Initialize the main dashboard class. */
Ictoria_Admin_Menu::instance();
```

</details>

## References

- https://developer.wordpress.org/reference/functions/add_action/
- https://developer.wordpress.org/reference/hooks/rest_api_init/
- https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
- https://developer.wordpress.org/reference/functions/wp_enqueue_style/
- https://developer.wordpress.org/reference/functions/wp_enqueue_script/
- https://www.php.net/manual/en/function.strtolower.php
- https://www.php.net/manual/en/function.str-replace.php
- https://www.php.net/manual/en/function.strpos.php
- https://www.php.net/manual/en/function.file-exists.php
- https://www.php.net/manual/en/function.spl-autoload-register.php
