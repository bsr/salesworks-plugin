<?php 

/*-----------------------------------------------------------------------------------*/
/*	 Contextual Category  Authors 
/*   http://pointandstare.com
/*   Usage: Widget
/*-----------------------------------------------------------------------------------*/

class WP_Widget_PandS_Cat_Authors_Widget extends WP_Widget {
	function WP_Widget_PandS_Cat_Authors_Widget() {
		$widget_ops = array( 'classname' => 'widget_PandSCAW', 'description' => __( "Displays a list of authors who have contributed to the current category and links to their profile." ) );
		$this->WP_Widget('PandSCAW', __('List Contextual Category Authors'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args);
		if (is_category()) {
			$current_category = get_the_category();
			$cat_parent = get_parent_id($current_category); // get id of parent category
			if(isset($cat_parent)) { 

				echo $before_widget; ?>
				<div class="pands-caw-widget">
					<?php
						$cat_parent_id = $cat_parent[0];
						$cat_parent_slug = $cat_parent[1];
						$cat_parent_name = $cat_parent[2];
						$author_array = array();
						$args = array(
							'numberposts' => -1,
							'category_name' => $cat_parent_name,
							'orderby' => 'author',
							'order' => 'ASC'
						);
						$cat_posts = get_posts($args);
						echo "<div class=\"widget-header\">";
						echo $cat_parent_name;
						echo " Authors</div>";
						echo "<ul>";
						foreach ($cat_posts as $cat_post) :
							if (!in_array($cat_post->post_author,$author_array)) {
								$author_array[] = $cat_post->post_author;
							}
						endforeach;
						foreach ($author_array as $author) :
							$auth = get_userdata($author)->display_name;
							$auth_email = get_userdata($author)->user_email;
							$auth_email = get_userdata($author)->user_email;
							$auth_link = get_userdata($author)->user_login;
							$auth_url = "/author/".$auth_link;
							if($auth != 'admin') {
								echo "<li>";
								echo "<div class='author_pic'><a href='".$auth_url."'>";
								echo get_avatar( $auth_email, 60 );
								echo "</a></div>";
								echo "<div class='author_info'><a href='".$auth_url."'>";
								echo $auth.'</a><br/>Rand Group';
								echo "</div><div class='clear'></div>";
								echo "</li>";
							}
						endforeach;
						echo "</ul>";
					?>
				
			</div>
			<?php }
			echo $after_widget;
			} // has a cat parent
		} // is category
	} // function widget
add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_PandS_Cat_Authors_Widget");'));


?>