<?php
/**
 * Plugin Name: Unused Image Cleaner
 * Plugin URI: https://github.com/ovick1997/unused-image-cleaner
 * Description: A plugin to identify and clean unused images in the media library.
 * Version: 1.0
 * Author: Md Shorov Abedin
 * Author URI: https://shorovabedin.com
 * License: GPL2
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include necessary files
include_once plugin_dir_path( __FILE__ ) . 'includes/class-uic-admin.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/class-uic-media.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/class-uic-ajax.php';

// Initialize the admin functionality
function uic_initialize_plugin() {
    $admin = new UIC_Admin();
    $media  = new UIC_Media();
    $ajax   = new UIC_Ajax();
}
add_action( 'plugins_loaded', 'uic_initialize_plugin' );
