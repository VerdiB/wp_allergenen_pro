# IAM_Base_Page

This is for reference when creating a new page file, the base classes should normally not have to be changed. <sub>_\* With the exception of changing the top-level submenu title._</sub>

```php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class IAM_Base_Page
{
    /* Each page needs these variables, the use of these variables
     * is shown in comments in the other docs.
     */
    protected $page_title;
    protected $menu_title;
    protected $capability;
    protected $menu_slug;
    protected $callback;
    protected $icon_url = '';
    protected $position = null;

    /* The $sections array holds all sections that were added to
     * `IAM_Page_Testing_Menu::$page_sections` array and registered during its `__contruct()`
     * during that foreach loop `IAM_Base_Page::add_section()` is used
     * to add them to `IAM_Base_Page::$sections`
     */
    protected $sections = [];

    /* The instantiation of pages gets done here to remove clutter from
     * a new page file.
     *
     * This $instances array holds the instances of each page loaded via
     * `Ictoria_Admin_Menu::__construct()`
     */
    private static $instances = [];

    public static function instance()
    {
        $calledClass = static::class;
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new static();
        }
        return self::$instances[$calledClass];
    }

    /* This __construct() gets called from `IAM_Page_Testing_Menu::__construct()`
     * during its own construct.
     *
     * It uses `parent::__construct()` to call this class' construct with the
     * variables required.
     */
    public function __construct($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url = '', $position = null)
    {
        /* Here it sets the provided variables to the
         * `IAM_Base_Page` instances' variables.
         */
        $this->page_title = $page_title;
        $this->menu_title = $menu_title;
        $this->capability = $capability;
        $this->menu_slug = $menu_slug;
        $this->callback = $callback;
        $this->icon_url = $icon_url;
        $this->position = $position;

        /* Register autoload function for sections */
        spl_autoload_register([$this, 'autoload']);

        /* Add menu callback */
        add_action('admin_menu', [$this, 'register_menu']);
    }

    /* This autoloader looks for section files in the sections directory of a page.
     * e.g. `page_testing-menu/sections`
     */
    public function autoload($class_name)
    {
        if (strpos($class_name, 'IAM_Page') === 0) {
            /* Get the calling class name using Reflection
             * https://www.php.net/manual/en/class.reflectionclass.php
             */
            $reflection = new ReflectionClass($this);

            /* e.g. iam-page-testing-menu.php */
            $class_file = $reflection->getFileName();

            /* e.g. page_testing-menu */
            $parent_class_dir = basename(dirname($class_file));

            /* Convert class name to file name */
            $file_name = str_replace('_', '-', strtolower($class_name)) . '.php';

            /* Construct the file path */
            $file_path = IAM_DIR . '/' . $parent_class_dir . '/sections/' . $file_name;

            /* Load the file if it exists */
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }

    /* The register_menu() method uses add_menu_page & add_submenu_page to
     * register each page.
     * It uses the boolean value set in `IAM_Page_Testing_Menu::is_top_level()`.
     *
     * If it is a top-level menu it runs both the add_menu_page and add_submenu_page
     * for that page, this will make sure a submenu item gets added for the top-level
     * menu with a custom title.
     *
     * As seen in `page_ictoria-dashboard/iam-page-ictoria-dashboard.php`
     * the `IAM_Page_Ictoria_Dashboard::$menu_title` is set to 'Ictoria'.
     * By using this add_submenu_page we set the $parent_slug to the $menu_slug.
     *
     * This makes it so that the submenu item named 'Dashboard' will
     * link to the top-level page.
     *
     * https://developer.wordpress.org/reference/functions/add_menu_page/
     * https://developer.wordpress.org/reference/functions/add_submenu_page/
     */
    public function register_menu()
    {
        if ($this->is_top_level()) {
            add_menu_page(
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                [$this, 'render_page'],
                $this->icon_url,
                $this->position
            );
            add_submenu_page(
                $this->menu_slug, /* set parent_slug to current menu_slug */
                $this->page_title,
                'Dashboard', /* Set a custom title for the top-level submenu */
                $this->capability,
                $this->menu_slug,
                [$this, 'render_page']
            );
        } else {
            add_submenu_page(
                $this->get_parent_slug(), /* e.g. `IAM_Page_Testing_Menu::get_parent_slug()` */
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->menu_slug,
                [$this, 'render_page']
            );
        }
    }

    /* This is the default render_page callback, this can be overwritten in
     * the page file.
     * The only thing that should stay consistent is the div with wrap class
     */
    public function render_page()
    {
        if (!current_user_can($this->capability)) {
            return;
        }

    /* echo '<div class="wrap">';
     * - Keep everything inside this wrap div for it to adhere to WordPress look and feel
     * - https://codex.wordpress.org/index.php?title=Creating_Options_Pages&oldid=97268
     * echo '</div>';
     */
        echo '<div class="wrap">';

        // Call the method responsible for rendering sections
        $this->render_sections();

        echo '</div>';
    }

    /* This renders each section in a wrapper div with class
     * the classname of the page file. e.g. 'iam-ictoria-dashboard-section-add-allergen'
     * The $enable_header argument can be used to enable/disable
     * the h2 with the section title.
     */
    protected function render_sections($enable_header = true)
    {
        foreach ($this->sections as $section) {
            /* Ensure the section is an instance of IAM_Base_Section */
            if ($section instanceof IAM_Base_Section) {
                /* Call the section's callback method */
                echo '<div class="' . $section->get_section_class() . '">';
                if ($enable_header) {
                    echo '<h2>' . $section->get_section_title() . '</h2>';
                }
                $section->section_callback();
                echo '</div>';
            }
        }
    }

    /* Function used to add sections during the page construct */
    public function add_section($section)
    {
        /* Ensure no duplicate sections are added */
        foreach ($this->sections as $existing_section) {
            if ($existing_section->get_section_id() === $section->get_section_id()) {
                return; /* Section already exists */
            }
        }
        $this->sections[] = $section;
    }

    /* Function used to set a page to top-level, expects a boolean */
    /* Should always return false for submenus */
    abstract protected function is_top_level();
    /* Function used to set a submenu to the top-level $menu_slug, expects a string */
    /* Should always be empty e.g. '' for top-level menu */
    abstract protected function get_parent_slug();
}
```
