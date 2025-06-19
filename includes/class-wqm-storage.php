<?php
/**
 * Handles quote storage (DB table or CPT)
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WQM_Storage
 *
 * This class manages the storage of quotes either in a custom database table or as a custom post type.
 */
class WQM_Storage {
    const TABLE = 'wqm_quotes';
    const CPT = 'wqm_quote';

    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        register_activation_hook(WP_PLUGIN_DIR . '/wp-quote-management/wp-quote-management.php', [$this, 'maybe_create_table']);
    }

    /**
     * Registers the custom post type for quotes
     */
    public function register_cpt() {
        $args = [
            'public' => false,
            'show_ui' => false,
            'label' => 'Bonza Quotes',
            'supports' => ['title', 'custom-fields'],
        ];
        register_post_type(self::CPT, $args);
    }

    /**
     * Creates the quotes table if it doesn't exist
     */
    public function maybe_create_table() {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            service_type varchar(255) NOT NULL,
            notes text,
            status varchar(20) NOT NULL DEFAULT 'pending',
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Inserts a new quote into the database or custom post type
     *
     * @param array $data Quote data
     * @return int|false Inserted ID or false on failure
     */
    public static function insert_quote($data) {
        $type = WQM_Settings::get_storage_type();
        if ($type === WQM_Settings::STORAGE_CPT) {
            return self::insert_quote_cpt($data);
        }
        return self::insert_quote_db($data);
    }

    /**
     * Inserts a new quote into the database table
     *
     * @param array $data Quote data
     * @return int|false Inserted ID or false on failure
     */
    public static function insert_quote_db($data) {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;
        $wpdb->insert($table, [
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_email($data['email']),
            'service_type' => sanitize_text_field($data['service_type']),
            'notes' => sanitize_textarea_field($data['notes']),
            'status' => 'pending',
        ]);
        return $wpdb->insert_id;
    }

    /**
     * Inserts a new quote as a custom post type
     *
     * @param array $data Quote data
     * @return int|false Inserted post ID or false on failure
     */
    public static function insert_quote_cpt($data) {
        $post_id = wp_insert_post([
            'post_type' => self::CPT,
            'post_title' => sanitize_text_field($data['name']),
            'post_status' => 'publish',
            'meta_input' => [
                'email' => sanitize_email($data['email']),
                'service_type' => sanitize_text_field($data['service_type']),
                'notes' => sanitize_textarea_field($data['notes']),
                'status' => 'pending',
            ],
        ]);
        return $post_id;
    }

    /**
     * Retrieves all quotes from the database or custom post type
     *
     * @return array List of quotes
     */
    public static function get_quotes() {
        $type = WQM_Settings::get_storage_type();
        if ($type === WQM_Settings::STORAGE_CPT) {
            return self::get_quotes_cpt();
        }
        return self::get_quotes_db();
    }

    /**
     * Retrieves all quotes from the database table
     *
     * @return array List of quotes
     */
    public static function get_quotes_db() {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;
        return $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC", ARRAY_A);
    }

    /**
     * Retrieves all quotes from the custom post type
     *
     * @return array List of quotes
     */
    public static function get_quotes_cpt() {
        $args = [
            'post_type' => self::CPT,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];
        $posts = get_posts($args);
        $quotes = [];
        foreach ($posts as $post) {
            $quotes[] = [
                'id' => $post->ID,
                'name' => $post->post_title,
                'email' => get_post_meta($post->ID, 'email', true),
                'service_type' => get_post_meta($post->ID, 'service_type', true),
                'notes' => get_post_meta($post->ID, 'notes', true),
                'status' => get_post_meta($post->ID, 'status', true),
                'created_at' => $post->post_date,
            ];
        }
        return $quotes;
    }

    /**
     * Updates the status of a quote
     *
     * @param int $id Quote ID
     * @param string $status New status
     * @return bool|int True on success, false on failure, or number of rows affected
     */
    public static function update_status($id, $status) {
        $type = WQM_Settings::get_storage_type();
        if ($type === WQM_Settings::STORAGE_CPT) {
            return update_post_meta($id, 'status', $status);
        }
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;
        return $wpdb->update($table, ['status' => $status], ['id' => $id]);
    }
}
