<?php

/*
 * Plugin Name: StreamCast
 * Plugin URI:  https://wordpress.org/plugins/streamcasthttps://wordpress.org/
 * Description: Play iceCast, Shoutcast, Radioco, Radionomy Live stream in Wordpress.
 * Version: 2.3.8
 * Author: bPlugins
 * Author URI: http://bPlugins.com
 * License: GPLv2
 * Text Domain:  streamcast
 * Domain Path:  /languages
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( function_exists( 'str_fs' ) ) {
    str_fs()->set_basename( false, __FILE__ );
} else {
    // Some Setup
    define( 'STP_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'STP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
    define( 'STP_PLUGIN_VERSION', '2.3.8' );
    if ( !function_exists( 'str_fs' ) ) {
        function str_fs() {
            global $str_fs;
            if ( !isset( $str_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_6433_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_6433_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $str_fs = fs_dynamic_init( array(
                    'id'              => '6433',
                    'slug'            => 'streamcast',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_a19d159db561c020210345da466f1',
                    'is_premium'      => false,
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                        'days'               => 7,
                        'is_require_payment' => true,
                    ),
                    'has_affiliation' => 'selected',
                    'menu'            => array(
                        'slug'        => 'edit.php?post_type=streamcast',
                        'first-path'  => 'edit.php?post_type=streamcast&page=streamcast#/dashboard',
                        'support'     => false,
                        'affiliation' => false,
                    ),
                    'is_live'         => true,
                ) );
            }
            return $str_fs;
        }

        // Init Freemius.
        str_fs();
        // Signal that SDK was initiated.
        do_action( 'str_fs_loaded' );
    }
    // Load main plugin class
    require_once plugin_dir_path( __FILE__ ) . 'inc/class-streamcast.php';
    // Initialize
    StreamCast::instance();
}