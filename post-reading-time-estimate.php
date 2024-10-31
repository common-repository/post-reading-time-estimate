<?php

/**
 * Plugin Name: Post Reading Time Estimate
 * Description: Add an estimated reading time to your posts, pages, or custom post types.
 * Author:      wpfoodie
 * Version:     1.1.4
 * Author URI:  https://wpfoodie.com
 * Upgrade URI: https://wpfoodie.com
 */

/**
 * The current version of the plugin.
 */
define( 'READING_TIME_ESTIMATE_VERSION', '1.1.4' );

if ( ! function_exists( 'get_reading_time_estimate_plugin_file' ) ):

	function get_reading_time_estimate_plugin_file() {
		return __FILE__;
	}

endif;

if ( ! function_exists( 'get_reading_time_estimate_dir' ) ) :

	function get_reading_time_estimate_dir() {
		return dirname( __FILE__ );
	}
	
endif;

if ( ! function_exists( 'get_reading_time_estimate_inc_folder' ) ):

	function get_reading_time_estimate_inc_folder() {
		return dirname( __FILE__ ) . '/inc';
	}

endif;

if ( ! function_exists( 'get_reading_time_estimate_plugin_url' ) ):

	function get_reading_time_estimate_plugin_url() {
		return plugin_dir_url( __FILE__ );
	}

endif;

function activate_reading_time_estimate() {
	do_action( 'reading_time_estimate_activation' );
}

register_activation_hook( get_reading_time_estimate_plugin_file(), 'activate_reading_time_estimate' );

/**
 * Initialize
 */
require_once( get_reading_time_estimate_inc_folder() . '/classes/class-post-reading-time-estimate.php' );

add_action( 'plugins_loaded', array( Reading_Time_Estimate(), 'initialize' ) );
