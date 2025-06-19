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
- Action: `WQM_quote_submitted` fires after a quote is submitted.

## Security
- All inputs are sanitized and validated.
- Nonces are used for form and admin actions.