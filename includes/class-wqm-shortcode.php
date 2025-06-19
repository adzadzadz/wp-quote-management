<?php
/**
 * Handles the [bonza_quote_form] shortcode and form submission
 */
if (!defined('ABSPATH')) {
    exit;
}

class WQM_Shortcode {
    public function __construct() {
        add_shortcode('bonza_quote_form', [$this, 'render_form']);
        add_action('init', [$this, 'handle_form_submission']);
    }

    public function render_form() {
        if (isset($_GET['wqm_success'])) {
            echo '<div class="wqm-success">' . esc_html__('Quote submitted successfully!', 'wp-quote-bonza') . '</div>';
        }
        ob_start();
        ?>
        <form method="post" class="wqm-quote-form">
            <?php wp_nonce_field('wqm_quote_submit', 'wqm_nonce'); ?>
            <p>
                <label><?php _e('Name', 'wp-quote-bonza'); ?></label><br>
                <input type="text" name="wqm_name" required />
            </p>
            <p>
                <label><?php _e('Email', 'wp-quote-bonza'); ?></label><br>
                <input type="email" name="wqm_email" required />
            </p>
            <p>
                <label><?php _e('Service Type', 'wp-quote-bonza'); ?></label><br>
                <input type="text" name="wqm_service_type" required />
            </p>
            <p>
                <label><?php _e('Notes', 'wp-quote-bonza'); ?></label><br>
                <textarea name="wqm_notes"></textarea>
            </p>
            <p>
                <input type="submit" name="wqm_submit" value="<?php esc_attr_e('Submit Quote', 'wp-quote-bonza'); ?>" />
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Handles the form submission and stores the quote
     */
    public function handle_form_submission() {
        if (!isset($_POST['wqm_submit'])) {
            return;
        }
        if (!isset($_POST['wqm_nonce']) || !wp_verify_nonce($_POST['wqm_nonce'], 'wqm_quote_submit')) {
            return;
        }
        $data = [
            'name' => sanitize_text_field($_POST['wqm_name']),
            'email' => sanitize_email($_POST['wqm_email']),
            'service_type' => sanitize_text_field($_POST['wqm_service_type']),
            'notes' => sanitize_textarea_field($_POST['wqm_notes']),
        ];
        $id = WQM_Storage::insert_quote($data);
        do_action('wqm_quote_submitted', $id, $data);
        wp_redirect(add_query_arg('wqm_success', 1, wp_get_referer()));
        exit;
    }
}
