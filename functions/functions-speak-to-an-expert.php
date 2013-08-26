<?php

/*-----------------------------------------------------------------------------------*/
/*	Expert Shortcode
/*-----------------------------------------------------------------------------------*/

function tz_expert( $atts, $content) {
   
	// don't use contractor, admin, colin, jason, brandee, bryan 
	$exclude_list = array('admin','salesworks','colin','jcarroll','bbarker','bvillanueva','odesk');

	// get expert from post author
	$title = get_the_title();
	
	// get author info
	$avatar = get_avatar( get_the_author_meta( 'ID' ), '96' );
	
	$user_login = get_the_author_meta( 'user_login' );
	
	$display_name = get_the_author_meta( 'display_name' );
	
	$job_title = get_the_author_meta( 'job_title' );
	
	$author_description = get_the_author_meta( 'description' );
	
	
	
	$author_first_name = get_the_author_meta( 'first_name' );
	
	// if the author name doesnt match the exclude list						
	if(!in_array($user_login, $exclude_list)){          						

		$return = '
	<h3>Talk to the '.$title.' Expert:</h3>
	<div class="author-profile vcard row-fluid expert">
		<div class="span2 pull-left">
			<div class="author_image">'.$avatar.'</div>
		</div>
		
		<div class="span10 pull-right">
			<div class="author-profile-inner">
				
				<p class="author-name fn n"><strong>'.$display_name.'</strong></p>
				<p class="job_title">'.$job_title.' at Rand Group</a></p>
			</div>
			<div class="author-description author-bio">'.$author_description.'</div>
			<p><a class="btn-primary btn" href="/contact/?expert=' . $user_login . '">Send a Message</a><p>
			
		</div>

	</div>';

		return $return;
		
	}

}
			

add_shortcode('expert', 'tz_expert');

?>