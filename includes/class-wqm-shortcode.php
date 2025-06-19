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

}
