# Main Entry File

The main entry file for the dashboard is [ictoria-admin-menu.php](../../ictoria-admin-menu.php). This file loads in all the pages, utilities and its corrsponding assets.

Besides [ictoria-admin-menu.php](../../ictoria-admin-menu.php) there also is [ictoria-admin-menu.css](../../ictoria-admin-menu.css) and [ictoria-admin-menu.js](../../ictoria-admin-menu.js), these files get enqueued in the `iam_style()` & `iam_script()` functions.

### Setup & Autoloader

Each page had its own directory `page_title`, entry files `iam-page-title.php` and sections `sections/iam-page-title-section-title.php`, it is important to follow the naming & file structure for everything to load automatically.

- **$directories** <br>- The directories array holds the paths of directories inside `dashboard` that need to be checked by the autoloader, **it is important to add onto this if you make a new page**.
- **$autoload_styles & $autoload_scripts** <br>- These arrays are populated during the main `autoload()` to hold the scripts and styles for each page so they can be enqueued.
