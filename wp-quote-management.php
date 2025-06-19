<?php
/*
Plugin Name: WP Quote Management
Plugin URI: https://adzbyte.com
Description: A simple plugin to manage quotes in WordPress.
Version: 1.0.0
Author: Adrian T. Saycon <adzbite@gmail.com>
Author URI: https://adzbyte.com/adz
Text Domain: wp-quote-management
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin version constant
define( 'WQM_VERSION', '1.0.0' );

// Include required files
require_once __DIR__ . '/includes/class-wqm-storage.php';
require_once __DIR__ . '/includes/class-wqm-loader.php';

// Register activation hook for table creation
register_activation_hook( __FILE__, [ 'WQM_Storage', 'maybe_create_table' ] );

// Main plugin runner
if ( ! function_exists( 'wqm_run_plugin' ) ) {
    function wqm_run_plugin() {
        $loader = new WQM_Loader();
        $loader->run();
    }
}
add_action( 'plugins_loaded', 'wqm_run_plugin' );