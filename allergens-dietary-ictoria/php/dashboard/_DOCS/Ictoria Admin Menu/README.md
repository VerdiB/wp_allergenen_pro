# Ictoria Admin Menu

This adds a WordPress Admin menu to the WordPress dashboard sidebar named `Ictoria`, which will be the main interface for Ictoria plugins i.e. `Allergens & Dietary`.

## Loading pages

When loading in the [dashboard/ictoria-admin-menu.php](../../ictoria-admin-menu.php) file it instantiates itself and registers the pages/submenus via an autoloader. Each page extends the [IAM_Base_Page](../../utilities/base_classes/iam-base-page.php) class.

It automatically loads all pages that are added into the `$directories` array, it also loads in the [base classes](../Ictoria%20Admin%20Menu/Utilities/Base%20Classes/README.md) [IAM_Base_Page](../Ictoria%20Admin%20Menu/Utilities/Base%20Classes/IAM_Base_Page.md) and [IAM_Base_Section](../Ictoria%20Admin%20Menu/Utilities/Base%20Classes/IAM_Base_Section.md), this is where the main logic of the pages and sections resides, things like child instantiation, hook registration, settings, options etc.

## Naming & File Structure

For the file naming structure there is a simple logic by always using dashes ( - ) to join words and adding nested items onto the file in the same pattern.

- PHP File Names _(e.g. iam-page-ictoria-dashboard)_:

  - **base ( iam ):** <br>- In the main `dashboard` dir there is a file called `ictoria-admin-menu.php`, this is the dashboards' main entry or index file, this will be referenced to in abbreviated form e.g. `IAM/iam`.
  - **base-page-title ( iam-page-ictoria-dashboard ):** <br>- This references that is is a `page` and this always needs to be followed by the page name e.g. `ictoria-dashboard`.
  - **base-page-title-section-title ( iam-page-ictoria-dashboard-section-add-allergen ):** <br>- This follows the same logic, reference it as a section and add the title.

- PHP Class Names _(e.g. IAM_Page_Ictoria_Dashboard)_: <br>- Class names follow a similar logic but instead of dashes ( - ) it uses underscores ( \_ ) and everything is capitalized except for abbreviations which should be full caps.

- Directory File Names:

  - **pages ( dashboard/page_title/ ):** <br>- When creating a page directory the naming should be the title with `page_` prepended, e.g. `page_ictoria-dashboard`.
  - **sections ( dashboard/page_title/sections/ ):** <br>- For sections it is a little different, sections are a sub-directory of a page. So each `sections` dir holds multiple sections

For CSS/JS files it follows the same rules as the PHP files, as long as they are directly related to the page/section, and should be placed directly next to it i.e. in the same root.

- CSS/JavaScript File Names:

  - **CSS ( \*.css ):** <br>- `iam-page-ictoria-dashboard.css`
  - **JavaScript ( \*.js ):** <br>- `iam-page-ictoria-dashboard.js`

The naming structure used is for the autoloaders to work properly without using actual namespaces. The naming technically only has to be followed for items that are **directly** related to pages or sections.

The files that follow this naming could/should be considered as indexes, as they initiate the chain for all it's appended items e.g. `iam-page-title-section-title` is loaded in via `iam-page-title`.

## Database_Connect & Rest_Routes

Everything is set-up to make it as simple as possible to output custom HTML, CSS & JavaScript. There is a are REST API routes to do basic CRUD actions on the database via the functions defined in `database_connect.php` which implement the functions from the `DB` directory.

## Pages & Sections

Each page is built up out of sections which are located in the dedicated `page_my-submenu/sections` directory.
