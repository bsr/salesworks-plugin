<?php

/*-----------------------------------------------------------------------------------*/
/*	Add custom post type for Testimonials
/*-----------------------------------------------------------------------------------*/

add_action('init', 'testimonials_register');
 
function testimonials_register() {
 
	$labels = array(
		'name' => _x('Testimonials', 'post type general name'),
		'singular_name' => _x('Testimonial', 'post type singular name'),
		'add_new' => _x('Add New', 'Testimonial'),
		'add_new_item' => __('Add New Testimonial'),
		'edit_item' => __('Edit Testimonial'),
		'new_item' => __('New Testimonial'),
		'view_item' => __('View Testimonial'),
		'search_items' => __('Search Testimonial'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => plugins_url( '/images/icons/thumb-up.png', dirname(__FILE__) ), 
		'rewrite' => array('with_front' => false, 'slug' => 'testimonials'),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail','slug')
	  ); 
 
	register_post_type( 'testimonials' , $args );
}



// Show Meta-Box for Testimonials

add_action( 'admin_init', 'testimonials_create' );
 
function testimonials_create() {
    add_meta_box('testimonials_meta', 'Testimonials', 'testimonials_meta', 'testimonials','normal','high');
}
 
function testimonials_meta () {
	 
	// - grab data -
	 
	global $post;	
	$custom = get_post_custom($post->ID);
	$meta_quote = $custom["testimonials_quote"][0];
	$meta_fullname = $custom["testimonials_fullname"][0];
	$meta_job_title = $custom["testimonials_job_title"][0];
	$meta_company = $custom["testimonials_company"][0];
	$meta_city = $custom["testimonials_city"][0];
	$meta_state = $custom["testimonials_state"][0];
	$meta_link = $custom["testimonials_link"][0];
	$meta_link_copy = $custom["testimonials_link_copy"][0];
	 
	// - security -
	 
	echo '<input type="hidden" name="testimonials-nonce" id="testimonials-nonce" value="' .
	wp_create_nonce( 'testimonials-nonce' ) . '" />';
	 
	// - output -
	 
	?>
	<div class="meta-box testimonials-meta">
	<ul>
		<li><label>Quotation: </label><textarea name="testimonials_quote"><?php echo $meta_quote; ?></textarea><strong>Do not include quotation marks.</strong></li>
		<li><label>Full Name: </label><input name="testimonials_fullname" value="<?php echo $meta_fullname; ?>" />  <em>(optional)</em></li>
		<li><label>Job Title: </label><input name="testimonials_job_title" value="<?php echo $meta_job_title; ?>" />  <em>(optional)</em></li>
		<li><label>Company: </label><input name="testimonials_company" value="<?php echo $meta_company; ?>" / > <em>(optional)</em></li>
		<li><label>City: </label><input name="testimonials_city" value="<?php echo $meta_city; ?>" / > <em>(optional)</em></li>
		<li><label>State: </label><input name="testimonials_state" value="<?php echo $meta_state; ?>" / > <em>(optional)</em></li>
		<li><label>Link: </label><input name="testimonials_link" value="<?php echo $meta_link; ?>" size="100" /> <em>(optional)</em></li>
		<li><label>Link Copy: </label><input name="testimonials_link_copy" value="<?php echo $meta_link_copy; ?>"  /> <em>(optional)</em></li>
	</ul>
	</div>
	
	<?php
}

// Save Data
 
add_action ('save_post', 'save_testimonials');
 
function save_testimonials(){
	 
	global $post;
	 
	// - still require nonce
	 
	if ( !wp_verify_nonce( $_POST['testimonials-nonce'], 'testimonials-nonce' )) {
		return $post->ID;
	}
	 
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	 
	// - update post
	 

	if(!isset($_POST["testimonials_quote"])):
	return $post;
	endif;
	$testimonials_quote = $_POST["testimonials_quote"];
	update_post_meta($post->ID, "testimonials_quote", $testimonials_quote );
	 
	if(!isset($_POST["testimonials_fullname"])):
	return $post;
	endif;
	$testimonials_fullname = $_POST["testimonials_fullname"];
	update_post_meta($post->ID, "testimonials_fullname", $testimonials_fullname );
	 
	if(!isset($_POST["testimonials_job_title"])):
	return $post;
	endif;
	$testimonials_job_title = $_POST["testimonials_job_title"];
	update_post_meta($post->ID, "testimonials_job_title", $testimonials_job_title );
	 
	if(!isset($_POST["testimonials_company"])):
	return $post;
	endif;
	$testimonials_company = $_POST["testimonials_company"];
	update_post_meta($post->ID, "testimonials_company", $testimonials_company );

	if(!isset($_POST["testimonials_city"])):
	return $post;
	endif;
	$testimonials_city = $_POST["testimonials_city"];
	update_post_meta($post->ID, "testimonials_city", $testimonials_city );

	if(!isset($_POST["testimonials_state"])):
	return $post;
	endif;
	$testimonials_state = $_POST["testimonials_state"];
	update_post_meta($post->ID, "testimonials_state", $testimonials_state );
	
	if(!isset($_POST["testimonials_link"])):
	return $post;
	endif;
	$testimonials_link = $_POST["testimonials_link"];
	update_post_meta($post->ID, "testimonials_link", $testimonials_link );
	 
	if(!isset($_POST["testimonials_link_copy"])):
	return $post;
	endif;
	$testimonials_link_copy = $_POST["testimonials_link_copy"];
	update_post_meta($post->ID, "testimonials_link_copy", $testimonials_link_copy );
 
}



// styles for admin
 
function testimonials_styles() {
    global $post_type;
    if( 'testimonials' != $post_type )
        return;
    wp_enqueue_style('testimonials_style', plugins_url( '/css/meta.css', dirname(__FILE__) ));
}
 
add_action( 'admin_print_styles-post.php', 'testimonials_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'testimonials_styles', 1000 );




//***********************************************************************************

/*
 * TESTIMONIALS SHORTCODES (CUSTOM POST TYPE)
 * http://www.noeltock.com/web-design/wordpress/how-to-custom-post-types-for-events-pt-2/
 * [events-full]
 */

// 1) FULL EVENTS
//***********************************************************************************

function testimonials ( $atts ) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '99', // # of events to show
		'id' => '0', 
		'style' => '1', 
		'post_type' => 'testimonials', 
		'orderby'=>'rand'
	 ), $atts));

	// ===== OUTPUT FUNCTION =====

	ob_start();

	// ===== LOOP: FULL EVENTS SECTION =====
	
	// - query -
	$custom_posts = new WP_Query();
	// if id not specified, random
	if($id == 0) { 
		$custom_posts->query('post_type='.$post_type.'&posts_per_page='.$limit.'&orderby=rand');
	} else { 
		$custom_posts->query('post_type='.$post_type.'&posts_per_page=1&p='.$id);
	}
	if ( $custom_posts->have_posts() ) {
		while ($custom_posts->have_posts()) : $custom_posts->the_post();

				// - custom variables -			
				$testimonials_quote = get_post_meta( $custom_posts->post->ID, 'testimonials_quote', true );
				$testimonials_fullname = get_post_meta( $custom_posts->post->ID, 'testimonials_fullname', true );
				$testimonials_job_title = get_post_meta( $custom_posts->post->ID, 'testimonials_job_title', true );
				$testimonials_company = get_post_meta( $custom_posts->post->ID, 'testimonials_company', true );
				$testimonials_city = get_post_meta( $custom_posts->post->ID, 'testimonials_city', true );
				$testimonials_state = get_post_meta( $custom_posts->post->ID, 'testimonials_state', true );
				$testimonials_link = get_post_meta( $custom_posts->post->ID, 'testimonials_link', true );
				$testimonials_link_copy = get_post_meta( $custom_posts->post->ID, 'testimonials_link_copy', true );
				if (strlen($testimonials_link) > 0 ) $testimonials_link_copy = 'Learn More';

				
				/*
				<div class="full-testimonials">
					<div class="quote">
						<?php echo $testimonials_quote; ?></>
					</div>
					<?php 
					if ((isset($testimonials_fullname)) || (isset($testimonials_job_title)) || (isset($testimonials_company)) || (isset($testimonials_city))) {
						echo '<ul class="quote-author">';
						if (strlen($testimonials_fullname) > 0) echo '<li>'.$testimonials_fullname.'</li>';
						if (strlen($testimonials_job_title) > 0) echo '<li>'.$testimonials_job_title.'</li>';
						if (strlen($testimonials_company) > 0) echo '<li>'.$testimonials_company.'</li>';
						if (strlen($testimonials_city) > 0) echo '<li>'.$testimonials_city.', '.$testimonials_state.'</li>';
						echo '</ul>';
					}
					if (strlen($testimonials_link) > 0) echo '<div class="testimonial button"><a href="'.$testimonials_link.'">'.$testimonials_link_copy.'</a></div>';
					?>
				</div>
				*/				
				
				?>
			
				<blockquote class="well">
					<p>
						&ldquo; <?php echo $testimonials_quote; ?> &rdquo;
					</p>
					<?php 
					if ((isset($testimonials_fullname)) || (isset($testimonials_job_title)) || (isset($testimonials_company)) || (isset($testimonials_city))) {
						echo '<cite  class="pull-right">';
						if (strlen($testimonials_fullname) > 0) echo ''.$testimonials_fullname.'';
						if (strlen($testimonials_job_title) > 0) echo ', '.$testimonials_job_title.'';
						if (strlen($testimonials_company) > 0) echo ' at '.$testimonials_company.'';
						if (strlen($testimonials_city) > 0) echo ' ('.$testimonials_city.', '.$testimonials_state.')';
						echo ' &mdash; </cite>';
					}
					if (strlen($testimonials_link) > 0) echo '<a class="pull-left" href="'.$testimonials_link.'">'.$testimonials_link_copy.'</a>';
					?>
				</blockquote>
				
					



		<?php
		endwhile; 
		} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
	


	// ===== RETURN: FULL testimonials SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output; 
	
}

add_shortcode('testimonials', 'testimonials'); // You can now call onto this shortcode with [events-full limit='20']




?>