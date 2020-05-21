<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined('WP_UNINSTALL_PLUGIN' ) ) {
  wp_die();
}

// Deleete All Options of this plugin
$pluginoptions = 'covtags_coronavirus_options';

// Delete options
delete_option($pluginoptions);
