# Pages

The dashboard exists out of pages, each page can be seen as a submenu[^submenu] item under the `Ictoria` sidebar entry of the WordPress Admin dashboard.

There is only one page that is different from the other and that is the
top-level[^top-level] page, for us that is `page_ictoria-dashboard`. This is the top-level menu and is the figurative parent to all the submenus' `page_*` directories.

[^submenu]: `submenu` https://codex.wordpress.org/Administration_Menus#Sub-Level_Menus
[^top-level]: `top-level` https://codex.wordpress.org/Administration_Menus#Top-Level_Menus

## Directory Structure

It is important to follow the [file naming structure](../README.md) for proper autoloading of files.

For each page we create a `page_some-title` directory where `some-title` is the title of the page and the access file `iam-page-some-title.php`, the directory structure and files would look something like this:

```bash
# Main ictoria-admin-menu directory
dashboard/
 |- page_ictoria-dashboard/
 |- page_allergens-dietary/
 |- page_settings/
 |  # new page directory
 |- page_some-title/
 |-  |- sections/
 |-  |-  |- iam-page-some-title-section-header.php
 |-  |- iam-page-some-title.php
 |   |  # These can just be empty
 |-  |- iam-page-some-title.css
 |-  |- iam-page-some-title.js
```

When adding a new page or changing the naming of an existing one you need to be sure that you also remove/change it where it is manually added to the [main entry file](../Main%20Entry%20File/README.md).

## Top-level menu

https://developer.wordpress.org/plugins/administration-menus/top-level-menus

The top-level menu is the main item that gets added to the WordPress admin menu.

- IAM_Page_Ictoria_Dashboard - [iam-page-ictoria-dashboard.php](../../../page_ictoria-dashboard/iam-page-ictoria-dashboard.php)

For the name/title (`Ictoria`) of the item added into the admin menu and the name/title (`Dashboard`) of the submenu that will appear when it is clicked, there is a check inside the [IAM_Base_Page::register_menu()](../../../utilities/base_classes/iam-base-page.php) method.

_**In the top-level page file it is thus important to set `is_top_level()` to return `true` and `get_parent_slug()` to return `''`:**_

```php
protected function is_top_level()
{
    return true; // true since it's top-level
}

protected function get_parent_slug()
{
    return ''; // No parent since it's top-level
}
```

Inside the [IAM_Base_Page::register_menu()](../../../utilities/base_classes/iam-base-page.php) the `is_top_level()` check, if `true` will do the `add_menu_page()` & `add_submenu_page()` actions. This will ensure that there is a submenu created for the top-level item and by setting the `section_id` for `add_submenu_page()` to the same `$menu_slug` it will load the same page for the submenu item.

### Top-level item submenu title

To set the title of the submenu for the top-level item only, you will need to change it in the [IAM_Base_Page::register_menu()](../../../utilities/base_classes/iam-base-page.php) `if $this->is_top_level() true add_submenu_page()` and set a custom `$menu_title`

<details>
  <summary>Click to show code (function register_menu()):</summary>

```php
public function register_menu()
{
    if ($this->is_top_level()) {
        /* https://developer.wordpress.org/reference/functions/add_menu_page/ */
        add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            [$this, 'render_page'],
            $this->icon_url,
            $this->position
        );
        /* https://developer.wordpress.org/reference/functions/add_submenu_page/ */
        add_submenu_page(
            $this->menu_slug,
            $this->page_title,
            'Dashboard', // <--- change top-level submenu title
            $this->capability,
            $this->menu_slug,
            [$this, 'render_page']
        );
    } else {
        /* https://developer.wordpress.org/reference/functions/add_submenu_page/ */
        add_submenu_page(
            $this->get_parent_slug(),
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            [$this, 'render_page']
        );
    }
}
```

</details>

## Code Explanation

This is based on the `page_allergens-dietary/iam-page-allergens-dietary.php` file.

```bash
# Main ictoria-admin-menu directory
dashboard/
 |- page_allergens-dietary/
 |-  |- sections/
 |-  |-  |- iam-page-allergens-dietary-section-add-allergen.php
 |-  |-  |- iam-page-allergens-dietary-section-manage-allergens.php
 |-  |- iam-page-allergens-dietary.php
 |-  |- iam-page-allergens-dietary.css
 |-  |- iam-page-allergens-dietary.js
```

The start of the file is just the basics, a lot is not needed because we extend the IAM_Base_Page class. The things we do have to note is the `$page_sections` as this is where we add the items from the `page_allergens-dietary/sections/` directory.

The naming in `$page_sections` is the end of the sections' class name, I'm still trying to figure something out to do it better and automatically, but for now we add the end of each section class name into the array.

e.g. `IAM_Page_Allergens_Dietary_Section_Add_Allergen` becomes `Add_Allergen`.

<details>
  <summary>Click to show code (class IAM_Page_Allergens_Dietary extends IAM_Base_Page):</summary>

```php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Allergens_Dietary extends IAM_Base_Page
{
    /* Insert our sections (if any) */
    private static $page_sections = [
        'Add_Allergen',
        'Manage_Allergens',
    ];

    public function __construct()
    {
        /* You can see the IAM_Base_Page::__construct() for these values */
        parent::__construct(
            'Allergens & Dietary Plugin',
            'Allergens & Dietary',
            'manage_options',
            'iam-allergens-dietary',
            [$this, 'render_page'],
            '',
            null,
        );

        /* Here it loops through each of the sections and uses
         * the IAM_Base_Page::add_section to add it to $sections.
         */
        foreach (self::$page_sections as $section_class) {
            $class_name = __CLASS__ . '_Section_' . $section_class;
            if (class_exists($class_name)) {
                $this->add_section(new $class_name);
            }
        }
    }

    protected function is_top_level()
    {
        return false; /* !important always false unless top-level */
    }

    protected function get_parent_slug()
    {
        /* !important always set to parent slug in this
         * case iam-dashboard, planning on changing this to
         * be default and remove these functions in submenus.
         */
        return 'iam-dashboard';
    }
```

</details>

### render_page()

This render_page function is the function that loads in the section_callback html and wraps it in a div with the menu-slug as class (e.g. iam-allergens-dietary). The `parent::render_sections(true)` is explained in [IAM_Base_Page](../Utilities/Base%20Classes/IAM_Base_Page.md).

<details>
  <summary>Click to show code (function render_page()):</summary>

```php
    /* The render_page() function can be added and
     * will override `IAM_Base_Page::render_page()`.
     * It needs to echo the html one way or another.
     */
    public function render_page()
    {
        echo '<div class="wrap">';

        /* <div class="iam-allergens-dietary"> */
        echo '<div class="' . $this->menu_slug . '">';

        /* <h1>Allergens & Dietary Plugin</h1> */
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

        /* iam-base-page.php->render_sections($enable_header = true)
         * shows the h2 header given in a sections' __construct() second argument ($section_title) */
          parent::render_sections(true);

        /* parent::render_sections(true); outputs this:
         *
         * <div class="iam-allergens-dietary-add-allergen">
         *  sections/iam-page-allergens-dietary-section-add-allergen.php-->render_sections()
         *  html gets output here ...
         * </div>
         * <div class="iam-allergens-dietary-manage-allergens">
         *  sections/iam-page-allergens-dietary-section-manage-allergens.php-->render_sections()
         *  html gets output here ...
         * </div>
         */

        echo '</h1>';
        echo '</div>';
    }
}
```

</details>
