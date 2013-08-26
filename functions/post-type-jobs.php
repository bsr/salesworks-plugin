<?php


/*-----------------------------------------------------------------------------------*/
/*	Add custom post type for Jobs (Job Postings)
/*-----------------------------------------------------------------------------------*/

add_action('init', 'jobs_register');
 
function jobs_register() {
 
	$labels = array(
		'name' => _x('Jobs', 'post type general name'),
		'singular_name' => _x('Job', 'post type singular name'),
		'add_new' => _x('Add New', 'Job'),
		'add_new_item' => __('Add New Job'),
		'edit_item' => __('Edit Job'),
		'new_item' => __('New Job'),
		'view_item' => __('View Job'),
		'search_items' => __('Search Job'),
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
		'menu_icon' => plugins_url( '/images/icons/xfn-friend.png', dirname(__FILE__) ), 	
		'rewrite' => array('with_front' => false, 'slug' => 'jobs'),
		'capability_type' => 'page',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail','author')
	  ); 
 
	register_post_type( 'jobs' , $args );
}

/*-----------------------------------------------------------------------------------*/
/* meta box
/* http://wp.tutsplus.com/tutorials/reusable-custom-meta-boxes-part-1-intro-and-basic-fields/
/*-----------------------------------------------------------------------------------*/



// Show Meta-Box for Jobs
global $post;	
$custom = get_post_custom($post->ID);
$meta_location = $custom["jobs_location"][0];
$meta_product = $custom["jobs_product"][0];
$meta_industry = $custom["jobs_industry"][0];
$meta_competency = $custom["jobs_competency"][0];

// Add the Meta Box  
function add_custom_meta_box() {  
    add_meta_box(  
        'jobs_meta_box', // $id  
        'Jobs Meta Box', // $title   
        'show_custom_meta_box', // $callback  
        'jobs', // $page
        'normal', // $context  
        'high'); // $priority  
}  
add_action('add_meta_boxes', 'add_custom_meta_box');  	
	
// Field Array  
$prefix = 'job_';  
$custom_meta_fields = array(  
    array(  
        'label'=> 'Location',  
        'desc'  => 'Is there an office this role is specific too?',  
        'id'    => $prefix.'location',  
        'type'  => 'select',  
        'options' => array (  
            'one' => array (  
                'label' => 'All',  
                'value' => 'all'  
            ),  
            'two' => array (  
                'label' => 'Houston, TX',  
                'value' => 'Houston, TX'  
            ),  
            'three' => array (  
                'label' => 'Dallas, TX',  
                'value' => 'Dallas, TX'  
            )  
        )
    ),  
    array(  
        'label'=> 'Product',  
        'desc'  => 'Is there a product line this posting is specific too?',  
        'id'    => $prefix.'select',  
        'type'  => 'select',  
        'options' => array (  
            'one' => array (  
                'label' => 'All',  
                'value' => 'all'  
            ),  
            'two' => array (  
                'label' => 'Microsoft Dynamics AX',  
                'value' => 'Microsoft Dynamics AX'  
            ),  
            'three' => array (  
                'label' => 'Microsoft Dynamics NAV',  
                'value' => 'Microsoft Dynamics NAV'  
            ),  
            'four' => array (  
                'label' => 'Microsoft Dynamics GP',  
                'value' => 'Microsoft Dynamics GP'  
            ),  
            'five' => array (  
                'label' => 'Project TrAX',  
                'value' => 'Project TrAX'  
            ),  
            'six' => array (  
                'label' => 'Microsoft Dynamics CRM',  
                'value' => 'Microsoft Dynamics CRM'  
            ),  
            'seven' => array (  
                'label' => 'Microsoft SharePoint',  
                'value' => 'Microsoft SharePoint'  
            ),  
            'eight' => array (  
                'label' => 'Microsoft SQL Server',  
                'value' => 'Microsoft SQL Server'  
            )  
        )
    ),  
    array(  
        'label'=> 'Industry',  
        'desc'  => 'Would industry experience be important?',  
        'id'    => $prefix.'select',  
        'type'  => 'select',  
        'options' => array (  
            'one' => array (  
                'label' => 'All',  
                'value' => 'all'  
            ),  
            'two' => array (  
                'label' => 'Upstream Oil & Gas',  
                'value' => 'Upstream Oil & Gas'  
            ),  
            'three' => array (  
                'label' => 'Manufacturing',  
                'value' => 'Manufacturing'  
            ),  
            'four' => array (  
                'label' => 'Construction',  
                'value' => 'Construction'  
            ),  
            'five' => array (  
                'label' => 'Distribution',  
                'value' => 'Distribution'  
            ),
            'six' => array (  
                'label' => 'Professional Services',  
                'value' => 'Professional Services'  
            )  
        )
    ),  
    array(  
        'label'=> 'Competency',  
        'desc'  => 'Department or ',  
        'id'    => $prefix.'select',  
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
function show_custom_meta_box() {  
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
function save_custom_meta($post_id) {  
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
add_action('save_post', 'save_custom_meta');  





// styles for admin
 
function jobs_styles() {
    global $post_type;
    if( 'jobs' != $post_type )
        return;
    wp_enqueue_style('jobs_style', plugins_url( '/css/meta.css', dirname(__FILE__) ));
}
 
add_action( 'admin_print_styles-post.php', 'jobs_styles', 1000 );
add_action( 'admin_print_styles-post-new.php', 'jobs_styles', 1000 );




/*-----------------------------------------------------------------------------------*/
/*	List All Jobs
/*-----------------------------------------------------------------------------------*/

function tz_jobs_list($atts, $content) {

	// - define arguments -
	extract(shortcode_atts(array(
		'limit' => '99', // # of events to show
		'id' => '0', 
		'style' => '1', 
		'post_type' => 'jobs',
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
		echo "<p>There are no job postings at this time.</p>";
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	
	


	// ===== RETURN: FULL jobs SECTION =====

	$output = ob_get_contents();
	ob_end_clean();
	return $output; 
	
	
}
add_shortcode('jobs_list', 'tz_jobs_list');

?>