<?php
/**
 * Main loader for Bonza Quote plugin
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WQM_Loader
 *
 * This class initializes the plugin by loading necessary components.
 */
class WQM_Loader {
    public function run() {
        require_once __DIR__ . '/class-wqm-settings.php';
        require_once __DIR__ . '/class-wqm-storage.php';
        require_once __DIR__ . '/class-wqm-shortcode.php';
        require_once __DIR__ . '/class-wqm-admin.php';
        require_once __DIR__ . '/class-wqm-hooks.php';

        new WQM_Settings();
        new WQM_Storage();
        new WQM_Shortcode();
        new WQM_Admin();
        new WQM_Hooks();
    }
}
