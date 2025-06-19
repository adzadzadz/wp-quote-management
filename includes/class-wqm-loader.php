<?php
/**
 * Main loader for Bonza Quote plugin
 */
if (!defined('ABSPATH')) {
    exit;
}

class WQM_Loader {
    public function run() {
        require_once __DIR__ . '/class-wqm-shortcode.php';
        require_once __DIR__ . '/class-wqm-storage.php';

        new WQM_Shortcode();
        new WQM_Storage();
    }
}
