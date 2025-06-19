<?php
/**
 * Settings page for storage toggle (DB table or CPT)
 */
if (!defined('ABSPATH')) {
    exit;
}

class WQM_Settings {
    const OPTION_KEY = 'WQM_storage_type';
    const STORAGE_DB = 'db';
    const STORAGE_CPT = 'cpt';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Adds the settings page to the WordPress admin menu
     */
    public function add_settings_page() {
        add_options_page(
            __('Bonza Quote Settings', 'wp-quote-management'),
            __('Bonza Quote', 'wp-quote-management'),
            'manage_options',
            'wqm-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Registers the settings for the plugin
     */
    public function register_settings() {
        register_setting('wqm_settings_group', self::OPTION_KEY);
    }

    /**
     * Renders the settings page for the plugin
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Bonza Quote Settings', 'wp-quote-management'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('wqm_settings_group'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Storage Type', 'wp-quote-management'); ?></th>
                        <td>
                            <select name="<?php echo esc_attr(self::OPTION_KEY); ?>">
                                <option value="db" <?php selected(get_option(self::OPTION_KEY, self::STORAGE_DB), 'db'); ?>><?php _e('Database Table', 'wp-quote-management'); ?></option>
                                <option value="cpt" <?php selected(get_option(self::OPTION_KEY, self::STORAGE_DB), 'cpt'); ?>><?php _e('Custom Post Type', 'wp-quote-management'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Returns the current storage type setting
     *
     * @return string 'db' or 'cpt'
     */
    public static function get_storage_type() {
        return get_option(self::OPTION_KEY, self::STORAGE_DB);
    }
}
