# IAM_Base_Section

This is for reference when creating a new section file, the base classes should normally not have to be changed.

```php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class IAM_Base_Section
{
    /* Each section needs these variables, the use of these variables
     * is shown in comments in the other docs.
     */
    protected $section_id;
    protected $section_title;
    protected $section_class;

    /* The construct is explained in 6. IAM_Base_Page and follows similar logic */
    public function __construct($section_id, $section_title, $section_class)
    {
        $this->section_id = $section_id;
        $this->section_title = $section_title;
        $this->section_class = $section_class;
    }

    /* This is the html output callback for what prepends the
     * table that gets output by `IAM_Base_page::render_page()`.
     *
     * Like the `IAM_Base_Page::render_page()` function the
     * `section_callback()` can be overwritten inside a
     * new section file.
     */
    public function section_callback()
    {
        echo '<p>Section description here.</p>';
    }

    /* Function for getting the $section_id */
    public function get_section_id()
    {
        return $this->section_id;
    }

    /* Function for getting the $section_title */
    public function get_section_title()
    {
        return $this->section_title;
    }

    /* Function for getting the $section_class */
    public function get_section_class()
    {
        return $this->section_class;
    }
}
```
