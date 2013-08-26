<?php
/**
 * @package SWX Widgets
 */
/*
Plugin Name: SWX Widgets & Shortcodes
Plugin URI: http://www.salesworks.com
Description: Custom widgets and Shortcodes
Version: 1.0
Author: Colin Greig
Author URI: http://www.colingreig.com
License: GPLv2 or later
*/

define( 'SWX_FILEPATH', plugin_dir_path(__FILE__) );

/*-----------------------------------------------------------------------------------*/
/*	Load Custom Post Types
/*-----------------------------------------------------------------------------------*/

require_once (SWX_FILEPATH . '/functions/post-type-testimonials.php');
require_once (SWX_FILEPATH . '/functions/post-type-events.php');
require_once (SWX_FILEPATH . '/functions/post-type-news.php');
require_once (SWX_FILEPATH . '/functions/post-type-squeeze-page.php');
require_once (SWX_FILEPATH . '/functions/post-type-awards.php');
// require_once (SWX_FILEPATH . '/functions/post-type-jobs.php');
// require_once (SWX_FILEPATH . '/functions/post-type-videos.php');



/*-----------------------------------------------------------------------------------*/
/*	Load Custom Functions / Shortcodes
/*-----------------------------------------------------------------------------------*/

require_once (SWX_FILEPATH . '/functions/functions-micro.php');
require_once (SWX_FILEPATH . '/functions/functions-speak-to-an-expert.php');
require_once (SWX_FILEPATH . '/functions/functions-ppc.php');
require_once (SWX_FILEPATH . '/functions/functions-pardot.php');
require_once (SWX_FILEPATH . '/functions/functions-custom-author-box.php');
require_once (SWX_FILEPATH . '/functions/functions-salesworks-methodology.php');
require_once (SWX_FILEPATH . '/functions/functions-social.php');
require_once (SWX_FILEPATH . '/functions/functions-big-headers.php');


/*-----------------------------------------------------------------------------------*/
/*	Load Custom Widgets
/*-----------------------------------------------------------------------------------*/

require_once (SWX_FILEPATH . '/functions/widget-aweber.php');
require_once (SWX_FILEPATH . '/functions/widget-contextual-categories.php');
require_once (SWX_FILEPATH . '/functions/widget-contextual-authors.php');
require_once (SWX_FILEPATH . '/functions/widget-download-monitor.php');




?>