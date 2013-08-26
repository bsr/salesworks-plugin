<?php


/*-----------------------------------------------------------------------------------*/
/*	 [aweber] Shortcode - get email from aweber confirmation url and write pardot iframe
/*-----------------------------------------------------------------------------------*/

function aweberPardot() {  
	// detect the product
	
/* 
Example URL
http://www.dynamics101.com/success/subscribed/?email=cgreig%40randgroup.com&from=cgreig%40randgroup.com&meta_adtracking=my_web_form&meta_message=1001&name=Colin%20&unit=d101-sharepoint&add_url=http%3A%2F%2Fwww.dynamics101.com%2Fsharepoint%2F&add_notes=96.53.64.78

Interest field value match
20751 ax
20761 gp
20771 nav
20781 crm
20791 sharepoint
20801 sql
*/

	$name = urlencode($_GET['name']);
	$email = urlencode($_GET['email']);
	$interest = $_GET['unit'];
		if($interest == 'd101-sharepoint') $interest = urlencode('Microsoft SharePoint');
		
	
	$string ='<iframe src="http://www2.dynamics101.com/l/20752/2013-04-26/8v2p/?email='.$email.'&interest='.$interest.'&first_name='.$name.'" width="1" height="1" style="display:none;"></iframe>';
	
	return $string;   
}  
add_shortcode('aweber', 'aweberPardot');  


/*-----------------------------------------------------------------------------------*/
/*	 aweber Widget 
/*-----------------------------------------------------------------------------------*/


class context_aweber_widget extends WP_Widget {
 
 
    /** constructor -- name this the same as the class above */
    function context_aweber_widget() {
        parent::WP_Widget(false, $name = 'Contextual Aweber Form');
    }
 
	/** @see WP_Widget::widget -- do not rename this */
	function widget($args, $instance) {
		extract( $args );
		$number 	= $instance['number']; // the number of categories to show
		$taxonomy 	= $instance['taxonomy']; // the taxonomy to display				
		$cat = get_the_category(); // get applicable categories	
		
		$cat_parent = get_parent_id($cat); // get id of parent category
		// if no posts in the parent category, don't show signup
		if($cat_parent === 0) { exit; }
		$cat_parent_id = $cat_parent[0];
		$cat_parent_slug = $cat_parent[1];
		$cat_parent_name = $cat_parent[2];
		// made manual tweak for CRM as its too long for aweber list name
		if($cat_parent_slug == 'microsoft-crm') { $cat_parent_slug = 'microsoftcrm'; }
		
		// debug 
		/*
			echo 'Value: '.$cat_parent_id;
			echo 'Value: '.$instance['title'];
			print_r($cat_parent);
		*/
		if(strlen($instance['title']) > 2) { 
			$title 		= apply_filters('widget_title', $instance['title'] ); // the widget title
		} else { 
			$title 		= apply_filters('widget_title', "Get ".$cat_parent_name." Tips Weekly"); // the widget title
		}
		$args = array(
			'number' 	=> $number,
			'taxonomy'	=> $taxonomy,
			'child_of'  => $cat_parent_id
		);
		
		// retrieves an array of categories or taxonomy terms
		
		?>
			  <?php echo $before_widget; ?>
				  <?php if ( $title ) { echo $before_title . $title . $after_title; } ?>
						<ul>
							<li>
								<!-- AWeber Web Form Generator 3.0 -->
								<style type="text/css">

								</style>
								<form method="post" class="af-form-wrapper" action="http://www.aweber.com/scripts/addlead.pl"  >
								<div style="display: none;">
									<input type="hidden" name="meta_web_form_id" value="1441519664" />
									<input type="hidden" name="meta_split_id" value="" />
									<input type="hidden" name="listname" value="d1-<?php echo $cat_parent_slug; ?>" />
									<input type="hidden" name="redirect" value="http://www.aweber.com/thankyou-coi.htm?m=text" id="redirect_78fa0f3b0f0aeb40b2096467e747e76e" />

									<input type="hidden" name="meta_adtracking" value="widget" />
									<input type="hidden" name="meta_message" value="1" />
									<input type="hidden" name="meta_required" value="email" />

									<input type="hidden" name="meta_tooltip" value="" />
								</div>
								<p>Our top <?php echo $cat_parent_name; ?> articles, delivered once per week.</p>
								<label class="previewLabel" for="awf_field-48324091">Your Email: </label>
								<input class="text" id="awf_field-48324091" type="text" name="email" value="" tabindex="502"  />
								<input name="submit" id="af-submit-image-1441519664" type="submit" alt="Submit Form" value="Subscribe" tabindex="503" />
								<p>We respect your <a title="Privacy Policy" href="http://www.dynamics101.com/terms-of-service-privacy-policy/" >email privacy</a></p>
								<div style="display: none;"><img src="http://forms.aweber.com/form/displays.htm?id=jCwsjKyMnGxsLA==" alt="" /></div>
								</form>
								<script type="text/javascript">
									<!--
									(function() {
										var IE = /*@cc_on!@*/false;
										if (!IE) { return; }
										if (document.compatMode && document.compatMode == 'BackCompat') {
											if (document.getElementById("af-form-1441519664")) {
												document.getElementById("af-form-1441519664").className = 'af-form af-quirksMode';
											}
											if (document.getElementById("af-body-1441519664")) {
												document.getElementById("af-body-1441519664").className = "af-body inline af-quirksMode";
											}
											if (document.getElementById("af-header-1441519664")) {
												document.getElementById("af-header-1441519664").className = "af-header af-quirksMode";
											}
											if (document.getElementById("af-footer-1441519664")) {
												document.getElementById("af-footer-1441519664").className = "af-footer af-quirksMode";
											}
										}
									})();
									-->
								</script>

								<!-- /AWeber Web Form Generator 3.0 -->
							</li>
							
						</ul>
			  <?php echo $after_widget; ?>
		<?php
	}
 
	/** @see WP_Widget::update -- do not rename this */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
 
        $title 		= esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php
    }
 
 
} // end class list_categories_widget
add_action('widgets_init', create_function('', 'return register_widget("context_aweber_widget");'));



?>