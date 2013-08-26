<?php

/*-----------------------------------------------------------------------------------*/
/*	Add custom post type for Event Pages
/*-----------------------------------------------------------------------------------*/

add_action('init', 'event_register');
function event_register() {
	$args = array(
		'label' => __('Events'),
		'singular_label' => __('Event'),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon' => plugins_url( '/images/icons/calendar.png', dirname(__FILE__) ), 
		'rewrite' => array('with_front' => false, 'slug' => 'events'),
		'has_archive' => true,		
		'supports' => array('title', 'editor', 'thumbnail')
		//'taxonomies' => array('category', 'post_tag') 
	);
	register_post_type( 'events' , $args );
}

// Show Meta-Box for Events

add_action( 'admin_init', 'events_create' );
 
function events_create() {
    add_meta_box('events_meta', 'Events', 'events_meta', 'events','normal','high');
}
 
function events_meta () {
	 
	// - grab data -
	 
	global $post;	
	$custom = get_post_custom($post->ID);
	$meta_sd = $custom["events_startdate"][0];
	$meta_ed = $custom["events_enddate"][0];
	$meta_location = $custom["events_location"][0];
	$meta_signup = $custom["events_signup"][0];
	$meta_st = $meta_sd;
	$meta_et = $meta_ed;
	 
	// - grab wp time format -
	 
	$date_format = get_option('date_format'); // Not required in my code
	$time_format = get_option('time_format');
	 
	// - populate today if empty, 00:00 for time -
	 
	if ($meta_sd == null) { $meta_sd = time(); $meta_ed = $meta_sd; $meta_st = 0; $meta_et = 0;}
	 
	// - convert to pretty formats -
	 
	$clean_sd = date("D, M d, Y", $meta_sd);
	$clean_ed = date("D, M d, Y", $meta_ed);
	$clean_st = date($time_format, $meta_st);
	$clean_et = date($time_format, $meta_et);
	 
	// - security -
	 
	echo '<input type="hidden" name="events-nonce" id="events-nonce" value="' .
	wp_create_nonce( 'events-nonce' ) . '" />';
	 
	// - output -
	 
	?>
	<div class="tf-meta">
	<ul>
		<li><label>Start Date </label><input name="events_startdate" class="tfdate" value="<?php echo $clean_sd; ?>" /></li>
		<li><label>Start Time </label><input name="events_starttime" value="<?php echo $clean_st; ?>" /> <em>Use 24h format (7pm = 19:00) EST</em></li>
		<li><label>End Date </label><input name="events_enddate" class="tfdate" value="<?php echo $clean_ed; ?>" /></li>
		<li><label>End Time </label><input name="events_endtime" value="<?php echo $clean_et; ?>" / ><em>Use 24h format (7pm = 19:00) EST</em></li>
		<li><label>Location </label><input name="events_location" value="<?php echo $meta_location; ?>" size="100" /> <em>(optional)</em></li>
		<li><label>Signup URL </label><input name="events_signup" value="<?php echo $meta_signup; ?>" size="100" /> <em>(optional)</em></li>
	</ul>
	</div>
	
	<?php
}

// Save Data
 
add_action ('save_post', 'save_events');
 
function save_events(){
	 
	global $post;
	 
	// - still require nonce
	 
	if ( !wp_verify_nonce( $_POST['events-nonce'], 'events-nonce' )) {
		return $post->ID;
	}
	 
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	 
	// - convert back to unix & update post
	 
	if(!isset($_POST["events_startdate"])):
	return $post;
	endif;
	$updatestartd = strtotime ( $_POST["events_startdate"] . $_POST["events_starttime"] );
	update_post_meta($post->ID, "events_startdate", $updatestartd );
	 
	if(!isset($_POST["events_enddate"])):
	return $post;
	endif;
	$updateendd = strtotime ( $_POST["events_enddate"] . $_POST["events_endtime"]);
	update_post_meta($post->ID, "events_enddate", $updateendd );

	if(!isset($_POST["events_location"])):
	return $post;
	endif;
	$events_location = $_POST["events_location"];
	update_post_meta($post->ID, "events_location", $events_location );

	if(!isset($_POST["events_signup"])):
	return $post;
	endif;
	$events_signup = $_POST["events_signup"];
	update_post_meta($post->ID, "events_signup", $events_signup );


 
}

// JS Datepicker UI
 
function events_styles() {
    global $post_type;
    if( 'events' != $post_type )
        return;
    wp_enqueue_style('ui-datepicker', plugins_url( '/css/jquery-ui-1.8.9.custom.css', dirname(__FILE__) ));
	
}
 
function events_scripts() {
    global $post_type;
    if( 'events' != $post_type )
        return;
	/*
    wp_enqueue_script('jquery-ui', get_bloginfo('template_url') . '/js/jquery-ui-1.8.9.custom.min.js', array('jquery'));
    wp_enqueue_script('ui-datepicker', get_bloginfo('template_url') . '/js/jquery.ui.datepicker.min.js');
    wp_enqueue_script('custom_script', get_bloginfo('template_url').'/js/pubforce-admin.js', array('jquery'));
	*/
    wp_enqueue_script('jquery-ui', plugins_url( '/js/jquery-ui-1.8.9.custom.min.js', dirname(__FILE__) ) , array('jquery'));
    wp_enqueue_script('ui-datepicker', plugins_url( '/js/jquery.ui.datepicker.min.js', dirname(__FILE__) ));
    wp_enqueue_script('custom_script', plugins_url( '/js/pubforce-admin.js', dirname(__FILE__) ), array('jquery'));


}
 
add_action( 'admin_print_styles-post.php', 'events_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'events_styles', 1000 );
 
add_action( 'admin_print_scripts-post.php', 'events_scripts', 1000 );
add_action( 'admin_print_scripts-post-new.php', 'events_scripts', 1000 );



//***********************************************************************************

/*
 * EVENTS SHORTCODES (CUSTOM POST TYPE)
 * http://www.noeltock.com/web-design/wordpress/how-to-custom-post-types-for-events-pt-2/
 * [events style="archive"]
 */

// 1) FULL EVENTS
//***********************************************************************************

function events ( $atts ) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '10', // # of events to show
		'style' => '1'
	 ), $atts));

	// ===== OUTPUT FUNCTION =====

	ob_start();

	// ===== LOOP: FULL EVENTS SECTION =====

	// - hide events that are older than 6am today (because some parties go past your bedtime) -

	$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );

	// - query -
	global $wpdb;
	$querystr = "
		SELECT *
		FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
		WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
		AND (metaend.meta_key = 'events_enddate' AND metaend.meta_value > $today6am )
		AND metastart.meta_key = 'events_enddate'
		AND wposts.post_type = 'events'
		AND wposts.post_status = 'publish'
		ORDER BY metastart.meta_value ASC LIMIT $limit
	 ";

	$events = $wpdb->get_results($querystr, OBJECT);

	// - declare fresh day -
	$daycheck = null;

	// - loop -
	if ($events) {
		global $post;

		// output 
		if( $style != 'archive') { echo  '<ol class="event-list list">'; }

		foreach ($events as $post):
			setup_postdata($post);

			// - custom variables -
			$custom = get_post_custom(get_the_ID());
			$sd = $custom["events_startdate"][0];
			$ed = $custom["events_enddate"][0];
			$signup = $custom["events_signup"][0];

			// - determine if it's a new day
			// - and not the "big list" style
			$longdate = date("l, F j, Y", $sd);
			
			if ($daycheck == null) { $edate = '<h4>' . $longdate . '</h4>'; }
			if ($daycheck != $longdate && $daycheck != null) { $edate = '<h4>' . $longdate . '</h4>'; }
			
			
			// - local time format -
			$time_format = 'F j';
			$stime = date($time_format, $sd);
			$etime = date($time_format, $ed);
			
			if($stime == $etime) { 
				$einfo = $stime;
			} else {
				$einfo = $stime . ' - ' . $etime;;
			}
			
			/*
			if($stime == $etime) { 
				$einfo = 'Event Date: '.$stime;
			} else {
				$einfo = 'Event Dates: '.$stime . ' - ' . $etime;;
			}
			*/
			
			// - date

			$title = get_the_title();

			// - output - 
			if($style == 'archive') { 			
			?>

				<article <? /*php post_class(); */?> >
					<?php if ( has_post_thumbnail()) : ?>
						<div class="row-fluid">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail(); ?></a>
						</div>
					<?php endif; ?>

					<div class="row-fluid entry-container">
							
							<div class="span12">
								<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<hr>
								<div class="entry-meta-info">
								<?php get_template_part('templates/entry-meta-events'); ?>		
								</div>
								<div class="entry-summary">
									<?php the_excerpt(); ?>
								</div>
							</div>
				</article>

			<?php
			} else { 
			?>
				<li>
					<h5><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo neat_trim($title, 60); ?></a></h5>
					<p class="date"><?php echo $einfo; ?></p>
				</li>
			<?php
			}
			
			// - fill daycheck with the current day -
			$daycheck = $longdate;

		endforeach;
		// output 
		if($style != 'archive') { echo '</ol>'; }
	
	} else {
	
		echo '<p>There are no upcoming events at this time.</p>';
		
	}

	// ===== RETURN: FULL EVENTS SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

add_shortcode('events', 'events'); // You can now call onto this shortcode with [events limit='20']



// 2) Just Meta Dates and Signup
//***********************************************************************************

function event_meta() {
	$custom = get_post_custom(get_the_ID());
	$sd = $custom["events_startdate"][0];
	$ed = $custom["events_enddate"][0];
	$location = $custom["events_location"][0];
	$signup = $custom["events_signup"][0];

	if(is_archive()) { 
		// - determine if it's a new day
		// - and not the "big list" style
		$longdate = date("F j, Y", $sd);
		echo '<p>';
		if ($daycheck == null) { echo '<span class="date">' . $longdate . '</span> '; }
		if (strlen($location)>0) { echo ' &mdash; <span class="location">' . $location . '</span> '; }
		// if (strlen($signup)>0) { echo do_shortcode('[button link="' . $signup . '"]Event Signup[/button]'); }
		echo '</p>';
		
	} else { 
	
		// - local time format -
		$time_format = 'l, F j, Y';
		$stime = date($time_format, $sd);
		$etime = date($time_format, $ed);

		if($stime == $etime) { 
			echo '<p class="date"><span>Event Date:</span> '.$stime.'</p>';
		} else {
			echo '<p class="date">Event Dates: '.$stime . ' to ' . $etime;'</p>';
		}
		if (strlen($location)>0) { echo '<p class="location">Event Location: ' . $location . '</p>'; }
		if (strlen($signup)>0) { echo do_shortcode('[button link="' . $signup . '"]Event Signup[/button]'); }
	}
}


// 1) FULL EVENTS
//***********************************************************************************

function events_full ( $atts ) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '10', // # of events to show
	 ), $atts));

	// ===== OUTPUT FUNCTION =====

	ob_start();

	// ===== LOOP: FULL EVENTS SECTION =====

	// - hide events that are older than 6am today (because some parties go past your bedtime) -

	$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );

	// - query -
	global $wpdb;
	$querystr = "
		SELECT *
		FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
		WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
		AND (metaend.meta_key = 'events_enddate' AND metaend.meta_value > $today6am )
		AND metastart.meta_key = 'events_enddate'
		AND wposts.post_type = 'events'
		AND wposts.post_status = 'publish'
		ORDER BY metastart.meta_value ASC LIMIT $limit
	 ";

	$events = $wpdb->get_results($querystr, OBJECT);

	// - declare fresh day -
	$daycheck = null;

	// - loop -
	if ($events):
	global $post;
	foreach ($events as $post):
	setup_postdata($post);

	// - custom variables -
	$custom = get_post_custom(get_the_ID());
	$sd = $custom["events_startdate"][0];
	$ed = $custom["events_enddate"][0];

	// - determine if it's a new day -
	$longdate = date("l, F j, Y", $sd);
	if ($daycheck == null) { echo '<h2 class="full-events">' . $longdate . '</h2>'; }
	if ($daycheck != $longdate && $daycheck != null) { echo '<h2 class="full-events">' . $longdate . '</h2>'; }

	// - local time format -
	$time_format = get_option('time_format');
	$stime = date($time_format, $sd);
	$etime = date($time_format, $ed);

	// - output - ?>
	<div class="full-events">
		<div class="text">
			<div class="title">
				<div class="time"><?php echo $stime . ' - ' . $etime; ?></div>
				<div class="eventtext"><?php the_title(); ?></div>
			</div>
		</div>
		 <div class="desc"><?php if (strlen($post->post_content) > 150) { echo substr($post->post_content, 0, 150) . '...'; } else { echo $post->post_content; } ?></div>
	</div>
	<?php

	// - fill daycheck with the current day -
	$daycheck = $longdate;

	endforeach;
	else :
	endif;

	// ===== RETURN: FULL EVENTS SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

add_shortcode('events-full', 'events_full'); // You can now call onto this shortcode with [events-full limit='20']



//***********************************************************************************
// Event .ICAL format
// Not sure how this functions
//***********************************************************************************
/*
function events_ical() {

// - start collecting output -
ob_start();

// - file header -
header('Content-type: text/calendar');
header('Content-Disposition: attachment; filename="ical.ics"');

// - content header -
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//<?php the_title(); ?>//NONSGML Events //EN
X-WR-CALNAME:<?php the_title(); _e(' - Events','NexTec'); ?>
X-ORIGINAL-URL:<?php echo the_permalink(); ?>
X-WR-CALDESC:<?php the_title(); _e(' - Events','NexTec'); ?>
CALSCALE:GREGORIAN

<?php

// - grab date barrier -
$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );
$limit = get_option('pubforce_rss_limit');

// - query -
global $wpdb;
$querystr = "
    SELECT *
    FROM $wpdb->posts wposts, $wpdb->postmeta metastart, $wpdb->postmeta metaend
    WHERE (wposts.ID = metastart.post_id AND wposts.ID = metaend.post_id)
    AND (metaend.meta_key = 'events_enddate' AND metaend.meta_value > $today6am )
    AND metastart.meta_key = 'events_enddate'
    AND wposts.post_type = 'events'
    AND wposts.post_status = 'publish'
    ORDER BY metastart.meta_value ASC LIMIT $limit
 ";

$events = $wpdb->get_results($querystr, OBJECT);

// - loop -
if ($events):
global $post;
foreach ($events as $post):
setup_postdata($post);

// - custom variables -
$custom = get_post_custom(get_the_ID());
$sd = $custom["events_startdate"][0];
$ed = $custom["events_enddate"][0];

// - grab gmt for start -
$gmts = date('Y-m-d H:i:s', $sd);
$gmts = get_gmt_from_date($gmts); // this function requires Y-m-d H:i:s, hence the back & forth.
$gmts = strtotime($gmts);

// - grab gmt for end -
$gmte = date('Y-m-d H:i:s', $ed);
$gmte = get_gmt_from_date($gmte); // this function requires Y-m-d H:i:s, hence the back & forth.
$gmte = strtotime($gmte);

// - Set to UTC ICAL FORMAT -
$stime = date('Ymd\THis\Z', $gmts);
$etime = date('Ymd\THis\Z', $gmte);

// - item output -
?>
BEGIN:VEVENT
DTSTART:<?php echo $stime; ?>
DTEND:<?php echo $etime; ?>
SUMMARY:<?php echo the_title(); ?>
DESCRIPTION:<?php the_excerpt_rss('', TRUE, '', 50); ?>
END:VEVENT
<?php
endforeach;
else :
endif;
?>
END:VCALENDAR
<?php
// - full output -
$tfeventsical = ob_get_contents();
ob_end_clean();
echo $tfeventsical;
}

function add_events_ical_feed () {
    // - add it to WP RSS feeds -
    add_feed('events-ical', 'events_ical');
}

add_action('init','add_events_ical_feed');
*/

?>