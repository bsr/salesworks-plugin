<?php


/*-----------------------------------------------------------------------------------*/
/*	Add custom post type for Videos (Video Postings)
/*-----------------------------------------------------------------------------------*/

add_action('init', 'videos_register');
 
function videos_register() {
 
	$labels = array(
		'name' => _x('Videos', 'post type general name'),
		'singular_name' => _x('Video', 'post type singular name'),
		'add_new' => _x('Add New', 'Video'),
		'add_new_item' => __('Add New Video'),
		'edit_item' => __('Edit Video'),
		'new_item' => __('New Video'),
		'view_item' => __('View Video'),
		'search_items' => __('Search Video'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => plugins_url( '/images/icons/film.png', dirname(__FILE__) ), 	
		'rewrite' => array('with_front' => false, 'slug' => 'videos'),
		'capability_type' => 'page',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail')
	  ); 
 
	register_post_type( 'videos' , $args );
}

/*-----------------------------------------------------------------------------------*/
/* meta box
/* http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields/
/*-----------------------------------------------------------------------------------*/

/*

// Show Meta-Box for Videos
global $post;	
$custom = get_post_custom($post->ID);
$meta_location = $custom["videos_location"][0];
$meta_height = $custom["videos_height"][0];
$meta_width = $custom["videos_width"][0];
$meta_file_type = $custom["videos_file_type"][0];

// Add the Meta Box  
function add_video_meta_box() {  
    add_meta_box(  
        'videos_meta_box', // $id  
        'Videos Meta Box', // $title   
        'show_video_meta_box', // $callback  
        'videos', // $page
        'normal', // $context  
        'high'); // $priority  
}  
add_action('add_meta_boxes', 'add_video_meta_box');  	
	
// Field Array  
$prefix = 'video_';  
$custom_meta_fields = array(  
    array(  
        'label'=> 'Video URL:',  
        'desc'  => 'What is the publicly accessible URL of the video?',  
        'id'    => $prefix.'location',   
        'type'  => 'text'  
    ),   
    array(  
        'label'=> 'Height',  
        'desc'  => '(Optional)',  
        'id'    => $prefix.'height',  
        'type'  => 'text'  
    ), 
    array(  
        'label'=> 'Width',  
        'desc'  => '(Optional)',  
        'id'    => $prefix.'height',  
        'type'  => 'text'  
    ), 
    array(  
        'label'=> 'File Type',  
        'desc'  => '### To be addressed ##',  
        'id'    => $prefix.'file_type',  
        'type'  => 'select',  
        'options' => array (  
            'one' => array (  
                'label' => 'All',  
                'value' => 'all'  
            ),  
            'two' => array (  
                'label' => 'Leadership',  
                'value' => 'Leadership'  
            ),  
            'three' => array (  
                'label' => 'Industry Experience',  
                'value' => 'Industry Experience'  
            ),  
            'four' => array (  
                'label' => 'Business, Accounting and Finance',  
                'value' => 'Business, Accounting and Finance'  
            ),  
            'five' => array (  
                'label' => 'Software Engineering and Development',  
                'value' => 'Software Engineering and Development'  
            ),
            'six' => array (  
                'label' => 'Human Relations',  
                'value' => 'Human Relations'  
            ),
            'seven' => array (  
                'label' => 'I.T. and Systems Administration',  
                'value' => 'I.T. and Systems Administration'  
            )    
        )
    )  
);  

// The Callback  
function show_video_meta_box() {  
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
function save_video_meta($post_id) {  
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
add_action('save_post', 'save_video_meta');  





// styles for admin
 
function videos_styles() {
    global $post_type;
    if( 'videos' != $post_type )
        return;
    wp_enqueue_style('videos_style', plugins_url( '/css/meta.css', dirname(__FILE__) ));
}
 
add_action( 'admin_print_styles-post.php', 'videos_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'videos_styles', 1000 );




/*-----------------------------------------------------------------------------------*/
/*	List All Videos
/*-----------------------------------------------------------------------------------*/

/*
function tz_videos_list($atts, $content) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '99', // # of events to show
		'id' => '0', 
		'style' => '1', 
		'post_type' => 'videos',
		'orderby'=>'rand'
	 ), $atts));


	// ===== OUTPUT FUNCTION =====

	ob_start();
	
	// - query -
	$custom_posts = new WP_Query();
	
	// if id not specified, random
	if($id == 0) { 
		$custom_posts->query('post_type='.$post_type.'&posts_per_page='.$limit.'&orderby=rand');
	} else { 
		$custom_posts->query('post_type='.$post_type.'&posts_per_page=1&p='.$id);
	}
	if ( $custom_posts->have_posts() ) {
		echo '<ul class="">';
		while ($custom_posts->have_posts()) : $custom_posts->the_post();

			$code_top = '<li>';
			$code_bottom = '</li>';
						
		

			echo $code_top;
			echo '<a href="'.get_permalink().'">'.get_the_title()."</a>";
			echo $code_bottom;

		endwhile;
		echo '</ul>';


	
	} else {
		// no posts found
		echo "<p>There are no video postings at this time.</p>";
	}
	// Restore original Post Data 
	wp_reset_postdata();
	
	


	// ===== RETURN: FULL videos SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output; 
	
	
}
add_shortcode('videos_list', 'tz_videos_list');
*/

?>