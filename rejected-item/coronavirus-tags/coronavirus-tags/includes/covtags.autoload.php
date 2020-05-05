<?php



// Error Reporting
error_reporting( 0 );
@ini_set( 'display_errors', 'Off' );


require_once ABSPATH     . 'wp-includes/pluggable.php';
require_once COVTAGS_DIR . 'includes/callbacks/covtags.vars.php';
require_once COVTAGS_DIR . 'includes/callbacks/covtags.filters.php';
require_once COVTAGS_DIR . 'includes/callbacks/covtags.actions.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.soft.compatibility.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.http.request.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.ui.php';
require_once COVTAGS_DIR . 'includes/classes/shortcodes.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.main.php';
