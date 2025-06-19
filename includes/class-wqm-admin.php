<?php
/**
 * Admin UI for managing quotes
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WQM_Admin
 *
 * This class handles the admin interface for managing quotes.
 */
class WQM_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_post_wqm_update_status', [$this, 'handle_status_update']);
    }

    /**
     * Adds the quotes management menu to the WordPress admin
     */
    public function add_menu() {
        add_menu_page(
            __('Bonza Quotes', 'wp-quote-management'),
            __('Bonza Quotes', 'wp-quote-management'),
            'manage_options',
            'wqm-quotes',
            [$this, 'render_quotes_page'],
            'dashicons-feedback',
            26
        );
    }

    /**
     * Renders the quotes management page
     */
    public function render_quotes_page() {
        $quotes = WQM_Storage::get_quotes();
        ?>
        <div class="wrap">
            <h1><?php _e('Bonza Quotes', 'wp-quote-management'); ?></h1>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Name', 'wp-quote-management'); ?></th>
                        <th><?php _e('Email', 'wp-quote-management'); ?></th>
                        <th><?php _e('Service Type', 'wp-quote-management'); ?></th>
                        <th><?php _e('Status', 'wp-quote-management'); ?></th>
                        <th><?php _e('Actions', 'wp-quote-management'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($quotes as $quote): ?>
                    <tr>
                        <td><?php echo esc_html($quote['name']); ?></td>
                        <td><?php echo esc_html($quote['email']); ?></td>
                        <td><?php echo esc_html($quote['service_type']); ?></td>
                        <td><?php echo esc_html(ucfirst($quote['status'])); ?></td>
                        <td>
                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                                <input type="hidden" name="action" value="wqm_update_status" />
                                <input type="hidden" name="id" value="<?php echo esc_attr($quote['id']); ?>" />
                                <?php wp_nonce_field('wqm_update_status_' . $quote['id'], 'WQM_status_nonce'); ?>
                                <select name="status">
                                    <option value="pending" <?php selected($quote['status'], 'pending'); ?>><?php _e('Pending', 'wp-quote-management'); ?></option>
                                    <option value="approved" <?php selected($quote['status'], 'approved'); ?>><?php _e('Approved', 'wp-quote-management'); ?></option>
                                    <option value="rejected" <?php selected($quote['status'], 'rejected'); ?>><?php _e('Rejected', 'wp-quote-management'); ?></option>
                                </select>
                                <input type="submit" value="<?php esc_attr_e('Update', 'wp-quote-management'); ?>" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Handles the status update for quotes
     */
    public function handle_status_update() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized', 'wp-quote-management'));
        }
        $id = intval($_POST['id']);
        $status = sanitize_text_field($_POST['status']);
        if (!isset($_POST['WQM_status_nonce']) || !wp_verify_nonce($_POST['WQM_status_nonce'], 'wqm_update_status_' . $id)) {
            wp_die(__('Invalid nonce', 'wp-quote-management'));
        }
        WQM_Storage::update_status($id, $status);
        wp_redirect(admin_url('admin.php?page=wqm-quotes'));
        exit;
    }
}
