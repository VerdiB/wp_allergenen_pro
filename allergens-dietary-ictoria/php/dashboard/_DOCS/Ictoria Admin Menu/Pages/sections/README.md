# Sections

For simplicity sake, sections can just be considered blocks of HTML.

Each section is to output some elements, again, naming is important here, not because of autoloading (except for the usual ones), but it is to make a clear reference and understanding of what gets output via this section (`class="iam-allergens-dietary-add-allergen"`), its parent-page (`class="iam-allergens-dietary"`) and maybe others.

This way we know that what gets output nested inside `'iam-allergens-dietary'` must be coming from the a `render_page()` and `'iam-allergens-dietary-some-title'` must be coming from a `section_callback()`. If these do not exist, the ones in [IAM_Base_Page](../../Utilities/Base%20Classes/IAM_Base_Page.md) or [IAM_Base_Section](../../Utilities/Base%20Classes/IAM_Base_Section.md) are being used and can be overwritten.

## Code Explanation

We start of with `__construct()` which uses the [IAM_Base_Section](../../Utilities/Base%20Classes/IAM_Base_Section.md)`::__construct()` to give the data needed for registration and loading etc.

<details>
  <summary>Click to show code (class IAM_Page_Allergens_Dietary_Section_Add_Allergen()):</summary>

```php
class IAM_Page_Allergens_Dietary_Section_Add_Allergen extends IAM_Base_Section
{
    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__), /* $section_id */
            __('Add Allergen', 'allergens-dietary-ictoria'), /* $section_title */
            str_replace('_', '-', strtolower(__CLASS__)), /* $section_class */
        );
    }
```

</details>

### get_section_class()

This function exists so the [IAM_Base_Page](../../Utilities/Base%20Classes/IAM_Base_Page.md)`::render_sections()` can insert the defined class name. This is for, as stated above, the logic of knowing what comes from where.

<details>
  <summary>Click to show code (function get_section_class()):</summary>

```php
    public function get_section_class()
    {
        return 'iam-allergens-dietary-add-allergen';
    }
```

</details>

### section_callback()

This is the main function that is used to output the HTML. In this example it is used to with some modified functions in `forms` directory to output the needed data as `arrays`. This then gets stored in variables so it can be referenced in the $html variable.

Do note that I am using [HEREDOC](https://www.phptutorial.net/php-tutorial/php-heredoc/) syntax for the HTML. This is not necessary, you could just echo the output (I do it in certain locations as well), but for large forms like this I feel like using [HEREDOC](https://www.phptutorial.net/php-tutorial/php-heredoc/) gives a better overview of what gets output as HTML by moving the PHP part to variables.

<details>
  <summary>Click to show code (function section_callback()):</summary>

```php
    public function section_callback()
    {
        if (!class_exists('Allergens_Dietary_Ictoria_Form')) {
            require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
        }
        /* forms/allergen_form.php */
        Allergens_Dietary_Ictoria_Form::setFormType(FormType::ALLERGENS);
        /* forms/allergen_add_allergen.php */
        $response = Allergens_Dietary_Ictoria_Form::getInstance()->showForm();

        // Allergen name values
        $html_allergenName_id = $response['fields']['allergen_name']['id'];
        $html_allergenName_label = $response['fields']['allergen_name']['label'];
        $html_allergenName_value = $response['fields']['allergen_name']['value'];

        // Allergen description values
        $html_allergenDescription_id = $response['fields']['allergen_description']['id'];
        $html_allergenDescription_label = $response['fields']['allergen_description']['label'];
        $html_allergenDescription_value = $response['fields']['allergen_description']['value'];

        // Allergen icon values
        $html_allergenIcon_id = $response['fields']['allergen_icon']['id'];
        $html_allergenIcon_label = $response['fields']['allergen_icon']['label'];

        // Submit button value
        $html_submitButton_value = $response['submit_button']['value'];

        $html = <<<HTML
        <form action="" method="post" enctype="multipart/form-data">
            <fieldset>
                <div>
                    <label for="{$html_allergenName_id}">{$html_allergenName_label}</label>
                    <input type="text" name="{$html_allergenName_id}" id="{$html_allergenName_id}" value="{$html_allergenName_value}"/>
                </div>

                <div>
                    <label for="{$html_allergenDescription_id}">{$html_allergenDescription_label}</label>
                    <input type="text" name="{$html_allergenDescription_id}" id="{$html_allergenDescription_id}" value="{$html_allergenDescription_value}"/>
                </div>

                <div>
                    <label for="{$html_allergenIcon_id}">{$html_allergenIcon_label}</label>
                    <input type="file" name="{$html_allergenIcon_id}" id="{$html_allergenIcon_id}"/>
                </div>

                <div>
                    <input type="submit" name="submit" class="button button-primary" value="{$html_submitButton_value}" />
                </div>
            </fieldset>
        </form>
        HTML;

        // this is what gets output by iam-page-allergens-dietary.php render_page() -> parent::render_sections(true);
        echo $html;
    }

}
```

</details>
