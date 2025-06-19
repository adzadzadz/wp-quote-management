# WP Quote Management Plugin

A WordPress plugin that provides a frontend quote form and admin management for quotes, with a toggle for storage as a custom database table or custom post type (CPT).

## Features
- Frontend shortcode form: `[bonza_quote_form]`
- Fields: Name, Email, Service Type, Notes
- Stores quotes as either DB table or CPT (toggle in settings)
- Admin menu to view and manage quotes
- Status management: Pending, Approved, Rejected
- Admin email notification on new quote
- Extensible via actions/filters

## Installation
1. Copy the `wp-quote-management` folder to your `wp-content/plugins/` directory.
2. Activate the plugin from the WordPress admin.
3. Visit Settings > Bonza Quote to choose storage type.
4. Use the `[bonza_quote_form]` shortcode on any page.

## Usage

### Displaying the Quote Form
To display the quote form on any page or post, simply add the following shortcode to your content editor:

```
[bonza_quote_form]
```

This will render a form with fields for Name, Email, Service Type, and Notes. When a user submits the form, the quote will be stored according to your selected storage method.

### Switching Storage Method
You can choose how quotes are stored—either in a custom database table or as a custom post type (CPT).

To switch storage usage:
1. Go to your WordPress admin dashboard.
2. Navigate to **Settings > Bonza Quote** (found in the left sidebar).
3. In the settings page, select your preferred storage method (Database Table or Custom Post Type).
4. Save your changes.

All new quotes will now be stored using the selected method. You can change this setting at any time.

## Automated Testing

### Requirements
- PHPUnit
- WordPress test environment (see https://make.wordpress.org/core/handbook/testing/automated-testing/)

### Running Tests
1. From the plugin directory, run:
   
   ```sh
   phpunit
   ```

2. Tests are located in the `tests/` directory.

## Extensibility
- Action: `wqm_quote_submitted` fires after a quote is submitted.

## Security
- All inputs are sanitized and validated.
- Nonces are used for form and admin actions.