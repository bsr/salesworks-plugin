<?php
/*-----------------------------------------------------------------------------------*/
/*	 REQUIRED Micro Functions et ID/Name/Slug of parent category 
/*-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*	 List Contextual Categories Widget Class
/*-----------------------------------------------------------------------------------*/

class list_categories_widget extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function list_categories_widget() {
        parent::WP_Widget(false, $name = 'List Contextual Categories');
    }
 
	/** @see WP_Widget::widget -- do not rename this */
	function widget($args, $instance) {
		extract( $args );
		$number 	= $instance['number']; // the number of categories to show
		$taxonomy 	= $instance['taxonomy']; // the taxonomy to display				
		$cat = get_the_category(); // get applicable categories	
		
		$cat_parent = get_parent_id($cat); // get id of parent category
		// if no posts in the parent category, don't show categories
		if($cat_parent === 0) { exit; }
		$cat_parent_id = $cat_parent[0];
		$cat_parent_slug = $cat_parent[1];
		$cat_parent_name = $cat_parent[2];
		// debug 
		/*
			echo 'Value: '.$cat_parent_id;
			echo 'Value: '.$instance['title'];
			print_r($cat_parent);
		*/
		if(strlen($instance['title']) > 2) { 
			$title 		= apply_filters('widget_title', $instance['title'] ); // the widget title
		} else { 
			$title 		= apply_filters('widget_title', $cat_parent_name." Categories"); // the widget title
		}
		$args = array(
			'number' 	=> $number,
			'taxonomy'	=> $taxonomy,
			'child_of'  => $cat_parent_id
		);
		
		// retrieves an array of categories or taxonomy terms
		$cats = get_categories($args);
		?>
			  <?php echo $before_widget; ?>
				  <?php if ( $title ) { echo $before_title . $title . $after_title; } ?>
						<ul>
							<?php foreach($cats as $cat) { ?>
								<li><a href="<?php echo get_term_link($cat->slug, $taxonomy); ?>" title="View all posts in <?php echo $cat->name; ?>"><?php echo $cat->name; ?></a></li>
							<?php } ?>
						</ul>
			  <?php echo $after_widget; ?>
		<?php
	}
 
	/** @see WP_Widget::update -- do not rename this */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['taxonomy'] = $new_instance['taxonomy'];
		return $instance;
	}
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
 
        $title 		= esc_attr($instance['title']);
        $number		= esc_attr($instance['number']);
        $exclude	= esc_attr($instance['exclude']);
        $taxonomy	= esc_attr($instance['taxonomy']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of categories to display'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
		<p>	
			<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Choose the Taxonomy to display'); ?></label> 
			<select name="<?php echo $this->get_field_name('taxonomy'); ?>" id="<?php echo $this->get_field_id('taxonomy'); ?>" class="widefat"/>
				<?php
				$taxonomies = get_taxonomies(array('public'=>true), 'names');
				foreach ($taxonomies as $option) {
					echo '<option id="' . $option . '"', $taxonomy == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				?>
			</select>		
		</p>
        <?php
    }
 
 
} // end class list_categories_widget
add_action('widgets_init', create_function('', 'return register_widget("list_categories_widget");'));
?>