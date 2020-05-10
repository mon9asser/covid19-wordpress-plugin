<?php

/**
 * CoronaVirus Tags
 *
 * @link              http://plugins.eratags.com/coronavirus-tags/
 * @since             1.0
 * @package           CoronaVirus Live Updates
 *
 * @CoronaVirus-tags
 * Plugin Name:       CoronaVirus Tags - CoronaVirus Live Updates
 * Plugin URI:        http://plugins.eratags.com/coronavirus-tags/
 * Description:       This Plugin provide you "CoronaVirus Live Statistics and last updates" in the whole world through table/widgets, shortcodes
 * Version:           1.0
 * Author:            EraTags
 * Author URI:        http://eratags.com
 * License:           license purchased
 * License URI:       https://codecanyon.net/licenses/terms/extended
 * Text Domain:       CORONAVIRUS_TAGS
 * Domain Path:       /languages
 */


 //  Exit if accessed directly.
  if ( !defined( 'ABSPATH' ) ){
      exit;
  }

// Define Constant and variables
define( 'COVTAGS_DIR',          plugin_dir_path( __FILE__ ) );
define( 'COVTAGS_SRC',          plugins_url( '/', __FILE__ ) );
define( 'COVTAGS_DIRNAME',      dirname( plugin_basename( __FILE__ ) ) );
define( 'COVTAGS_FILE',         __FILE__ );
define( 'COVTAGS_PHPVER',       phpversion() );
define( 'COVTAGS_VER',          '1.0' );
define( 'COVTAGS_TEXTDOMAIN',   'CORONAVIRUS_TAGS' );

// Require Basic Files
require_once COVTAGS_DIR . 'admin/class.administration.php';
require_once COVTAGS_DIR . 'includes/covtags.autoload.php';


// Setup Plugin
CovTags::_Run();
CovTags::_Disable();
