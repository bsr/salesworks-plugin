<?php

/*-----------------------------------------------------------------------------------*/
/*	Add custom post type for News Pages
/*-----------------------------------------------------------------------------------*/

add_action('init', 'news_register');
function news_register() {
	$args = array(
		'label' => __('News'),
		'singular_label' => __('News'),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_icon' => plugins_url( '/images/icons/newspaper.png', dirname(__FILE__) ), 
		'rewrite' => array('with_front' => false, 'slug' => 'news'),
		'has_archive' => true,		
		'supports' => array('title', 'editor', 'thumbnail')
		//'taxonomies' => array('category', 'post_tag') 
	);
	register_post_type( 'news' , $args );
}

// Show Meta-Box for News

add_action( 'admin_init', 'news_create' );
 
function news_create() {
    add_meta_box('news_meta', 'News', 'news_meta', 'news','normal','high');
}
 
function news_meta () {
	 
	// - grab data -
	 
	global $post;	
	 
	// - security -
	 
	echo '<input type="hidden" name="news-nonce" id="news-nonce" value="' .
	wp_create_nonce( 'news-nonce' ) . '" />';
	 
	// - output -
	 
	?>
	<?php
}

// Save Data
 
add_action ('save_post', 'save_news');
 
function save_news(){
	 
	global $post;
	 
	// - still require nonce
	 
	if ( !wp_verify_nonce( $_POST['news-nonce'], 'news-nonce' )) {
		return $post->ID;
	}
	 
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	 
 
}



//***********************************************************************************

/*
 * news SHORTCODES (CUSTOM POST TYPE)
 * http://www.noeltock.com/web-design/wordpress/how-to-custom-post-types-for-news-pt-2/
 * [news]
 */

// 1) FULL news
//***********************************************************************************

function news ( $atts ) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '10', // # of news to show
		'id' => '0', 
		'post_type' => 'news',
		// 'orderby'=>'rand',		
		'style' => 'default',
	 ), $atts));

	// ===== OUTPUT FUNCTION =====
	ob_start();
	
	// - query -
	$custom_posts = new WP_Query();
	if($id == 0) { 
		$custom_posts->query('post_type='.$post_type.'&posts_per_page='.$limit);
	} else { 
		$custom_posts->query('post_type='.$post_type.'&posts_per_page=1&p='.$id);
	}	
	if ( $custom_posts->have_posts() ) {
		echo '<ol class="news-list list '.$style.'">';
		while ($custom_posts->have_posts()) : $custom_posts->the_post();
			$title = get_the_title();
			
			// styling
			if($style == 'calendar') { 
				// calendar icon style
				?>
				<li>
					<div class="icon-date">
						 <h4><?php the_time('j'); ?></h4>				
						 <h3><?php the_time('M'); ?></h3>
					</div>
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo neat_trim($title, 60); ?></a>	
				</li>
			
				<?php
			} else { 
				// default style
				$days_ago = round( ( date('U') - get_the_time('U') ) / ( 60*60*24 ) );
				if($days_ago == 0) $timestamp = 'Posted today';
				elseif($days_ago == 1) $timestamp = 'Posted 1 day ago';
				else $timestamp = 'Posted '.$days_ago.' days ago.'; 
				?>
				<li>
					<div class="icon-date">
						<h4><?php the_time('j'); ?></h4>				
						<h3><?php the_time('M'); ?></h3>
					</div>
					<p><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo neat_trim($title, 60); ?></a></p>
					<!-- <p><?php echo $timestamp; ?></p> -->
				</li>
				
				<?php
			
			}

		endwhile;
		echo '</ol>';

	
	} else {
		// no posts found
		echo "<p></p>";
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
	


	// ===== RETURN: FULL careers SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output; 
	
}

add_shortcode('news', 'news'); // You can now call onto this shortcode with [news limit='20']




?>