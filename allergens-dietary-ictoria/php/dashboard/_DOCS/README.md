# Index

- [Ictoria Admin Menu](./Ictoria%20Admin%20Menu/README.md)
- [Main Entry File](./Ictoria%20Admin%20Menu/Main%20Entry%20File/README.md) [^ictoria-admin-menu]
- [Pages](./Ictoria%20Admin%20Menu/Pages/README.md)
  - [Sections](./Ictoria%20Admin%20Menu/Pages/sections/README.md)
- [Utilities](./Ictoria%20Admin%20Menu/Utilities/README.md)
  - [Base Classes](./Ictoria%20Admin%20Menu/Utilities/Base%20Classes/README.md)
    - [IAM_Base_Page](./Ictoria%20Admin%20Menu/Utilities/Base%20Classes/IAM_Base_Page.md) [^iam-base-page]
    - [IAM_Base_Section](./Ictoria%20Admin%20Menu/Utilities/Base%20Classes/IAM_Base_Section.md) [^iam-base-section]
  - [Rest Routes](./Ictoria%20Admin%20Menu/Utilities/Rest%20Routes.md) [^iam-rest-routes]
  - [Database Connect](./Ictoria%20Admin%20Menu/Utilities/Database%20Connect.md) [^iam-database-connect]
  - [Templates](./Ictoria%20Admin%20Menu/Utilities/Templates/README.md)

## To-do list

- [ ] REST API: <br>- Expand on functions in `iam-database-connect.php`, currently only changes things in `wp_allergens_dietary_ictoria_allergy` table.
- [ ] Split CSS: <br>- Move the CSS for sections to separate files and import them in the main page CSS file. This prevents clutter and keeps the mind set of keeping things together.
- [ ] Split JS: <br>- Same as Split CSS above.

## References

The code that this dashboard is based on (converted to OOP syntaxing and heavily modified):

- https://developer.wordpress.org/plugins/settings/custom-settings-page/

[^ictoria-admin-menu]: [ictoria-admin-menu.php](../ictoria-admin-menu.php)
[^iam-base-page]: [iam-base-page.php](../utilities/base_classes/iam-base-page.php)
[^iam-base-section]: [iam-base-section.php](../utilities/base_classes/iam-base-section.php)
[^iam-rest-routes]: [iam-rest-routes.php](../utilities/iam-rest-routes.php)
[^iam-database-connect]: [iam-database-connect.php](../utilities/iam-database-connect.php)
