<?php



// Error Reporting
error_reporting( E_ALL );
@ini_set( 'display_errors', 'On' );


require_once ABSPATH     . 'wp-includes/pluggable.php';
require_once COVTAGS_DIR . 'includes/callbacks/covtags.vars.php';
require_once COVTAGS_DIR . 'includes/callbacks/covtags.filters.php';
require_once COVTAGS_DIR . 'includes/callbacks/covtags.actions.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.soft.compatibility.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.http.request.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.ui.php';
require_once COVTAGS_DIR . 'includes/classes/shortcodes.php';
require_once COVTAGS_DIR . 'includes/classes/widget-map-card.php';
require_once COVTAGS_DIR . 'includes/classes/widget-live-card.php';
require_once COVTAGS_DIR . 'includes/classes/widget-ticker-card.php';
require_once COVTAGS_DIR . 'includes/classes/widget-stats-card.php';
require_once COVTAGS_DIR . 'includes/classes/widget-status-card.php';
require_once COVTAGS_DIR . 'includes/classes/covtags.main.php';
