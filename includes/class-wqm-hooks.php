<?php
/**
 * Hooks for extensibility and admin email notification
 */
if (!defined('ABSPATH')) {
    exit;
}

class WQM_Hooks {
    public function __construct() {
        add_action('wqm_quote_submitted', [$this, 'notify_admin'], 10, 2);
    }

    public function notify_admin($id, $data) {
        $admin_email = get_option('admin_email');
        $subject = __('New Quote Submitted', 'wp-quote-bonza');
        $message = sprintf(
            "%s\nEmail: %s\nService Type: %s\nNotes: %s",
            $data['name'],
            $data['email'],
            $data['service_type'],
            $data['notes']
        );
        wp_mail($admin_email, $subject, $message);
    }
}
