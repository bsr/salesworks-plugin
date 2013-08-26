<?php


/*-----------------------------------------------------------------------------------*/
/* meta box
/* http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields/
/*-----------------------------------------------------------------------------------*/



// Show Meta-Box for Jobs
global $post;	
$custom = get_post_custom($post->ID);
$meta_big_box = $custom["big_header_big_box"][0];
$meta_image = $custom["big_header_image"][0];
$meta_height = $custom["big_header_height"][0];

// Add the Meta Box  
function add_big_header_meta_box() {  
    add_meta_box(  
        'big_header_meta_box', // $id  
        'Big Header Meta Box', // $title   
        'show_big_header_meta_box', // $callback  
        'page', // $page
        'normal', // $context  
        'high'); // $priority  
}  
add_action('add_meta_boxes', 'add_big_header_meta_box');  	
	
// Field Array  
$prefix = 'big_header_';  
$custom_meta_fields = array(  
    array(  
        'label'=> 'Big Box',  
        'desc'  => 'Stuff some code or a slider in the header? (Optional)',  
        'id'    => $prefix.'big_box',  
        'type'  => 'textarea'  
    ),  
    array(  
        'label'=> 'OR Image URL',  
        'desc'  => 'Replace the page heading with an image? (Optional, 1170px wide)<br/>Include http://',  
        'id'    => $prefix.'image',  
        'type'  => 'text',  
    ),  
    array(  
        'label'=> 'Image Height',  
        'desc'  => 'Specify a specific height? (Optional, under 300px please)',  
        'id'    => $prefix.'height',  
        'type'  => 'text'  
    ), );  

// The Callback  
function show_big_header_meta_box() {  
	global $custom_meta_fields, $post;  
	// Use nonce for verification  
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
		  
	// Begin the field table and loop  
	echo '<table class="form-table">';  
	foreach ($custom_meta_fields as $field) {  
		// get value of this field if it exists for this post  
		$meta = get_post_meta($post->ID, $field['id'], true);  
		// begin a table row with  
		echo '<tr> 
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
				<td>';  
				switch($field['type']) {  
					// case items will go here  

					// text  
					case 'text':  
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" /> 
							<br /><span class="description">'.$field['desc'].'</span>';  
					break;  
					// textarea  
					case 'textarea':  
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea> 
							<br /><span class="description">'.$field['desc'].'</span>';  
					break;  
					// checkbox  
					case 'checkbox':  
						echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/> 
							<label for="'.$field['id'].'">'.$field['desc'].'</label>';  
					break;  
					// select  
					case 'select':  
						echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';  
						foreach ($field['options'] as $option) {  
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';  
						}  
						echo '</select><br /><span class="description">'.$field['desc'].'</span>';  
					break;  
					
				} //end switch  
		echo '</td></tr>';  
    } // end foreach  
    echo '</table>'; // end table  
}  


// Save the Data  
function save_big_header_meta($post_id) {  
    global $custom_meta_fields;  
      
    // verify nonce  
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))   
        return $post_id;  
    // check autosave  
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  
        return $post_id;  
    // check permissions  
    if ('page' == $_POST['post_type']) {  
        if (!current_user_can('edit_page', $post_id))  
            return $post_id;  
        } elseif (!current_user_can('edit_post', $post_id)) {  
            return $post_id;  
    }  
      
    // loop through fields and save the data  
    foreach ($custom_meta_fields as $field) {  
        $old = get_post_meta($post_id, $field['id'], true);  
        $new = $_POST[$field['id']];  
        if ($new && $new != $old) {  
            update_post_meta($post_id, $field['id'], $new);  
        } elseif ('' == $new && $old) {  
            delete_post_meta($post_id, $field['id'], $old);  
        }  
    } // end foreach  
}  
add_action('save_post', 'save_big_header_meta');  





// styles for admin
 
function big_header_styles() {
    global $post_type;
    if( 'jobs' != $post_type )
        return;
    wp_enqueue_style('jobs_style', plugins_url( '/css/meta.css', dirname(__FILE__) ));
}
 
add_action( 'admin_print_styles-post.php', 'big_header_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'big_header_styles', 1000 );




/********************************************************
 * Implement Big header
 * 
 ********************************************************/
/*
function awards ( $atts ) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '10', // # of awards to show
		'id' => '0', 
		'post_type' => 'awards',
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
		echo '<div class="awards-list list '.$style.'">';
		$i = 0;
		while ($custom_posts->have_posts()) : $custom_posts->the_post();
			$content = get_the_content();
			$title = get_the_title();
			// - custom variables -			
			$awards_url = get_post_meta( $custom_posts->post->ID, 'awards_url', true );		
			$awards_url_copy = get_post_meta( $custom_posts->post->ID, 'awards_url_copy', true );			
			if ( strlen($awards_url_copy) === 0 ) { $awards_url_copy = 'More Info'; }
				
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
				if($i>0) { echo '<div class="divideline"></div>'; }
				$i++; 
				
				?>
				<div class="row-fluid">
					<div class="span4">
						<?php if ( has_post_thumbnail()) : ?>
							<?php if ( strlen( $awards_url) > 0 ) { 
								echo '<a href="'.$awards_url.'">';
							}?>
							<?php the_post_thumbnail('medium'); ?>
							<?php if ( strlen( $awards_url) > 0 ) { 
								echo '</a>';
							}?>
						<?php endif; ?>
					</div>
					<div class="span8">
						<h3><?php echo $title; ?></h3>
						<?php echo '<p>'.$content.'</p>'; ?>
						<?php if ( strlen( $awards_url) > 0 ) { 
							echo '<p><a class="btn" href="'.$awards_url.'">';
							echo $awards_url_copy;
							echo '</a></p>';
						}?>
						
					</div>
				</div>
				
				<?php
				
			}
			
			

		endwhile;
		echo '</div>';

	
	} else {
		// no posts found
		echo "<p></p>";
	}
	// Restore original Post Data 
	wp_reset_postdata();
	
	


	// ===== RETURN: FULL careers SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output; 
	
}

add_shortcode('awards', 'awards'); // You can now call onto this shortcode with [awards limit='20']
*/

?>