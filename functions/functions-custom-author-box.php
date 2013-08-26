<?php

/*-----------------------------------------------------------------------------------*/
/*	 Author Fields - Add Role
*    http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
/*-----------------------------------------------------------------------------------*/
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">

		<tr>
			<th><label for="job_title">Job Title</label></th>

			<td>
				<input type="text" name="job_title" id="job_title" value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your job title.</span>
			</td>
		</tr>

		<tr>
			<th><label for="linkedin">LinkedIn</label></th>

			<td>
				<input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( get_the_author_meta( 'linkedin', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please paste the full URL of your LinkedIn profile. Eg: http://www.linkedin.com/in/ronrand/</span>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

			
	/* Copy and paste this line for additional fields. Make sure to change 'job_title' to the field ID. */
	update_user_meta( $user_id, 'job_title', $_POST['job_title'] );
	update_user_meta( $user_id, 'linkedin', $_POST['linkedin'] );
}

/*-----------------------------------------------------------------------------------*/
/*	 Dynamics 101 Author Box
/*   http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
/*   Usage: <?php my_author_box(); ?>
/*-----------------------------------------------------------------------------------*/
function my_author_box( $x = "" ) { 

	// don't show authorbox if admin
	if (get_the_author_meta('user_login') != 'admin') {

		// setup default company values for when custom user values dont exist
		$newsletter_url = '';
		if ( get_the_author_meta( 'linkedin' ) ) { 
			$linkedin = get_the_author_meta( 'linkedin' );
		} else { 
			// $linkedin = 'http://www.linkedin.com/company/the-rand-group-llc';
		}
		if ( get_the_author_meta( 'googleplus' ) ) { 
			$googleplus = get_the_author_meta( 'googleplus' );
		} else { 
			// $googleplus = 'https://plus.google.com/106481272899067718943/posts';
		}
		if ( get_the_author_meta( 'twitter' ) ) { 
			$twitter = get_the_author_meta( 'twitter' );
		} else { 
			// $twitter = 'rand_group';
		}
		
		

		

	?>
		<div class="author-profile vcard tbl-bottom">
			<div class="tbl-td one_third top center">
				<div class="author_image">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), '128' ); ?>
				</div>
			</div>
			
			<div class="tbl-td two_third top">
				<div class="author-profile-inner">
					

					<?php
					// if being used on author profile page, remove heading
					if($x != 'authorpage') { 
					?>				
						<h4 class="author-name fn n">Article written by <?php the_author_posts_link(); ?></h4>
					<?php } ?>
					
					<?php if ( get_the_author_meta( 'job_title' ) ) { ?>
						<p class="job_title">
							<a href="http://www.randgroup.com" title="Microsoft Dynamics <?php the_author_meta( 'job_title' ); ?> at Rand Group"><?php the_author_meta( 'job_title' ); ?> at Rand Group</a>
						</p>
					<?php } ?>

					<p class="author-description author-bio">
						<?php the_author_meta( 'description' ); ?>
					</p>

					<?php if ( isset($linkedin) ||  isset($googleplus) || isset($twitter) ) { ?>
						<p class="author-social">Follow <?php the_author_meta( 'first_name' ); ?>:  
							<?php if ( isset($linkedin) ) { ?>
									<a href="<?php echo $linkedin; ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on LinkedIn">LinkedIn</a>
							<?php } ?>
							<?php if ( isset($googleplus) ) { ?>
									<a rel="author" href="<?php echo $googleplus; ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on Google+">Google+</a>
							<?php }?>
							<?php if ( isset($twitter) ) { ?>
									<a href="http://twitter.com/<?php echo $twitter; ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on Twitter">Twitter</a>
							<?php } ?>
						</p>	
					<?php } ?>
				</div><!-- author-profile-inner -->
			
			</div>
			
		</div>
		<div class="clear"></div>
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/*	 Rand Group Author Box
/*   http://justintadlock.com/archives/2009/09/10/adding-and-using-custom-user-profile-fields
/*   Usage: <?php my_author_box(); ?>
/*-----------------------------------------------------------------------------------*/
function rand_author_box( $x = "" ) { 

	// setup default company values for when custom user values dont exist
	$newsletter_url = '';
	if ( get_the_author_meta( 'linkedin' ) ) { 
		$linkedin = get_the_author_meta( 'linkedin' );
	} else { 
		// $linkedin = 'http://www.linkedin.com/company/the-rand-group-llc';
	}
	if ( get_the_author_meta( 'googleplus' ) ) { 
		$googleplus = get_the_author_meta( 'googleplus' );
	} else { 
		// $googleplus = 'https://plus.google.com/106481272899067718943/posts';
	}
	if ( get_the_author_meta( 'twitter' ) ) { 
		$twitter = get_the_author_meta( 'twitter' );
	} else { 
		// $twitter = 'rand_group';
	}
	

?>

<div class="author-profile vcard row-fluid">

	<div class="span2 pull-left">
		<div class="author_image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '96' ); ?></div>
	</div>
	
	<div class="span10 pull-right">
		<div class="author-profile-inner"><?php // if being used on author profile page, remove heading
			if($x != 'authorpage') { 
			?>				
				<h4 class="author-name fn n">Article written by <?php the_author_posts_link(); ?></h4>
			<?php } ?>
		<p class="job_title">
			<?php if ( get_the_author_meta( 'job_title' ) ) { ?>
				<a href="http://www.randgroup.com" title="Microsoft Dynamics <?php the_author_meta( 'job_title' ); ?> at Rand Group"><?php the_author_meta( 'job_title' ); ?> at Rand Group</a>
			<?php } ?></p>
		</div>
		<div class="author-description author-bio"><?php the_author_meta( 'description' ); ?></div>
		<?php if ( isset($linkedin) ||  isset($googleplus) || isset($twitter) ) { ?>
			<div  class="author-social">
				<h4>Follow <?php the_author_meta( 'first_name' ); ?>:</h4>
					<ul>				
						<li><?php if ( isset($linkedin) ) { ?>
							<a href="<?php echo $linkedin; ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on LinkedIn" class="social-icon linkedin"></a><?php } ?></li>
						<li><?php if ( isset($googleplus) ) { ?>
							<a rel="author" href="<?php echo $googleplus; ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on Google+" class="social-icon googleplus"></a><?php }?></li>
						<li><?php if ( isset($twitter) ) { ?>
							<a href="http://twitter.com/<?php echo $twitter; ?>" title="Follow <?php the_author_meta( 'display_name' ); ?> on Twitter" class="social-icon twitter"></a><?php } ?></li>
					</ul>
			</div>
		<?php } ?>
	</div>

</div>



	<?php
}


?>