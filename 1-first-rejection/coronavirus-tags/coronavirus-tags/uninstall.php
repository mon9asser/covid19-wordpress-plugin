<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$pluginoptions = 'covtags_eratags_key_name';

delete_option($pluginoptions);
