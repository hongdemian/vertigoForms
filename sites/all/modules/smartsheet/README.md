# Summary
The Smartsheet module provides a wrapper class to easily interact with Smartsheet's REST API.

# Requirements
The xautoload module (https://drupal.org/project/xautoload) is required to automatically load the appropriate class.

# Installation
Install as you would normally install a contributed Drupal module. See: https://drupal.org/documentation/install/modules-themes/modules-7 for further information.

# Configuration
A valid Smartsheet access token is required for the module to work. It can be generated within the Smartsheet webapp (https://app.smartsheet.com/b/home).

With the previously generated token, the **smartsheet_access_token** configuration variable needs to be set. It can be done by two different ways:

- In the settings.php file, like this: `$conf['smartsheet_access_token'] = <GENERATED TOKEN>`
- With the help of the *smartsheet_ui* module. When this module is installed, a configuration interface is available at Administration » Configuration » Web services » Smartsheet API (_admin/config/services/smartsheet_). Once a valid token is set, the _Overview_ tab, that lists the available resources, shows up.

# Usage
The module provides the `SmartsheetAPI` wrapper class for the Smartsheet's REST API. An instance of the class can be created and retrieved with the static `SmartsheetAPI::instance()` method. This instance can then be used with these four methods:

- $instance->get($path, $parameters); *// GET request, parameters optionals*
- $instance->post($path, $parameters); *// POST request, parameters required*
- $instance->put($path, $parameters); *// PUT request, parameters required*
- $instance->delete($path); *// DELETE request, no parameters*

See the in-code documentation of the SmartsheetAPI class for more information and examples.

For further documentation about the Smartsheet REST API got to https://www.smartsheet.com/developers/api-documentation.

# Forms support
The module also provides a submit callback to insert values from a form into a sheet as a new row. To use it, just add three informations to your form:

- `$form['#submit'][] = 'smartsheet_form_submit';`
- `$form['#smartsheet_sheet_id'] = <SMARTSHEET ID>;` Where <SMARTSHEET ID> is the ID of the sheet inside which the values are to be inserted.
- `$form['#smartsheet_column_mapping'] = <FIELD MAPPING>;` Where <FIELD MAPPING> is an associative array whose keys are form field names and values are the matching column ids from the sheet. Each mapped value submitted in the form will be inserted in the matching column. For example:
```
$form['form_field_textfield'] = 3688246994279325;
$form['form_field_number'] = 8743389644660056;
$form['form_field_select'] = 4074863598216325;
```

# Todo
Here is a list of features I've thought about but had not enough time and/or ideas to implement:

- Create more classes to describe the different resources types.
- Maybe add some specific exceptions.
- If you have any ideas, use the issue queue, I'll be glad to read them.

# Contact
Current maintainers:
- Pierre Villette (villette) - https://www.drupal.org/user/2293492

This project has been sponsored by:
- Floe Design + Technologies - https://floedesign.ca
