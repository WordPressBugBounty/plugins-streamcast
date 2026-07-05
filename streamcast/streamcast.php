<?php

/*
 * Plugin Name: StreamCast – Live Radio Streaming Player
 * Plugin URI:  https://wordpress.org/plugins/streamcast
 * Description: Play iceCast, Shoutcast, Radioco, Radionomy Live stream in Wordpress.
 * Version: 2.4.3
 * Author: bPlugins
 * Author URI: http://bPlugins.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.2
 * Tested up to: 7.0
 * Text Domain:  streamcast
 * Domain Path:  /languages
 */

// ABS PATH
if (!defined('ABSPATH'))
    exit;


if (function_exists('str_fs')) {
    str_fs()->set_basename(false, __FILE__);
} else {
    // Some Setup
    define('STREAMCAST_PLUGIN_DIR', plugin_dir_url(__FILE__));
    define('STREAMCAST_PLUGIN_PATH', plugin_dir_path(__FILE__));
    define('STREAMCAST_PLUGIN_VERSION', '2.4.3');
    define('STREAMCAST_PLUGIN_FILE', __FILE__);
    define('STREAMCAST_HAS_PRO', 'streamcast-premium/streamcast.php' === plugin_basename(__FILE__));

    if (!function_exists('str_fs')) {
        function str_fs()
        {
            global $str_fs;
            if (!isset($str_fs)) {

                // Include Freemius SDK.
                require_once dirname(__FILE__) . '/vendor/freemius/start.php';
                $str_fs = fs_dynamic_init(array(
                    'id' => '6433',
                    'slug' => 'streamcast',
                    'type' => 'plugin',
                    'public_key' => 'pk_a19d159db561c020210345da466f1',
                    'is_premium' => false,
                    'menu' => array(
                        'slug' => 'edit.php?post_type=streamcast',
                        'first-path' => 'edit.php?post_type=streamcast&page=streamcast#/dashboard',
                        'support' => false,
                        'affiliation' => false,
                    ),
                    'is_live' => true,
                ));
            }
            return $str_fs;
        }

        // Init Freemius.
        str_fs();
        // Signal that SDK was initiated.
        do_action('str_fs_loaded');
    }



    // Load main plugin class
    require_once plugin_dir_path(__FILE__) . 'inc/class-streamcast-main.php';
    // Initialize
    new \StreamCast\STREAMCAST_Main();

}