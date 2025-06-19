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

if (!defined('ABSPATH')) {
	exit;
}

require_once __DIR__ . '/includes/class-wqb-loader.php';

function wqm_run_plugin()
{
	$loader = new WQM_Loader();
	$loader->run();
}
add_action('plugins_loaded', 'wqm_run_plugin');
