<?php

/**
 * Covtags - Coronavirus Live Updates
 *
 * @link              https://eratags.com/covtags
 * @since             1.0
 * @package           Covtags - Coronavirus Live Updates
 *
 * @Covtags
 * Plugin Name:       Covtags - Coronavirus Live Updates
 * Plugin URI:        https://eratags.com/covtags
 * Description:       This Plugin provide you "CoronaVirus Live Statistics and last updates" in the whole world through table/widgets, shortcodes, etc
 * Version:           1.0
 * Author:            EraTags
 * Author URI:        http://eratags.com
 * License:           license purchased
 * License URI:       https://codecanyon.net/licenses/terms/extended
 * Text Domain:       COVTAGS
 * Domain Path:       /languages
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/************************************************
 Define Constant and needed variables
*************************************************/
define( 'COVTAGS_DIR',        plugin_dir_path( __FILE__ ) );
define( 'COVTAGS_SRC',        plugins_url( '/', __FILE__ ) );
define( 'COVTAGS_DIRNAME',    dirname( plugin_basename( __FILE__ ) ) );
define( 'COVTAGS_PHPVER',     phpversion() );
define( 'COVTAGS_VER',        '1.0' );
define( 'COVTAGS_TEXTDOMAIN', 'ERATAGS_COVTAGS' );

/************************************************
Include Needed Files
*************************************************/

// Require wp files
require_once ABSPATH . 'wp-includes/pluggable.php';

// Core Files
require_once COVTAGS_DIR . 'incs/tags.class.software.php';
require_once COVTAGS_DIR . 'incs/tags.class.http.reqs.php';
require_once COVTAGS_DIR . 'incs/tags.class.ui.php';

// Actions - filters - hooks, etc
require_once COVTAGS_DIR . 'incs/tags.methods.helper.php';

// WP ( Shortcodes and widget api )
require_once COVTAGS_DIR . 'incs/tags.class.shortcodes.php';
require_once COVTAGS_DIR . 'incs/tags.widget.standard.php';

// Require Admin Files
require_once COVTAGS_DIR . 'admin/class.administration.php';


/************************************************
Start WP Hooks
*************************************************/

// Plugin Activation hook
if( ! function_exists( 'covtags_coronavirus_data_activation' ) ) {

  function covtags_coronavirus_data_activation() {

    // Classes
    $http_request = new covtags_http_requests();
    $software_com = new covtags_software_compatibility();

    // Install and save needed options for covid 19
    $http_request->covtags_save_coronavirus_options();

    // Clear the permalinks, etc
    flush_rewrite_rules();

  }

  register_activation_hook( __FILE__,  'covtags_coronavirus_data_activation' );

}

// Plugin deactivation hook
if( ! function_exists( 'covtags_coronavirus_data_deactivation' ) ) {

  function covtags_coronavirus_data_deactivation() {

    // unschedule Coronavirus event
    $covtags_next_event = wp_next_scheduled( 'covtags_coronavirus_cron_job' );
    if( $covtags_next_event !== false ) {
      wp_unschedule_event( $covtags_next_event, 'covtags_coronavirus_cron_job' );
    }

    // Clear the permalinks, etc
    flush_rewrite_rules();

  }

  register_deactivation_hook( __FILE__,  'covtags_coronavirus_data_deactivation' );

}

// Add Textdomain /Languages
if( ! function_exists( 'covtags_load_textdomain' ) ) {

  function covtags_load_textdomain() {
    load_plugin_textdomain( 'covtags', false, COVTAGS_DIRNAME . '/languages' );
  }
  add_action( 'init', 'load_plugin_textdomain' );

}

// ====>>>>> UI - Shortcode - Wideget - sequance by sequance
// $http_request = new covtags_http_requests();
//
// // Activate Cron Job
// $prr = $http_request->covtags_historical(  true );
//
// // Just for testing
// echo "<pre>";
// print_r( $prr );
// echo "</pre>";
// add_shortcode( 'covtags-standard',  function( $atts ){
//   $data = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
//   return $data;
// });
