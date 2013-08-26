<?php

/*-----------------------------------------------------------------------------------*/
/*	Social followus Icons
/*-----------------------------------------------------------------------------------*/


   


function followus($atts, $content = null) {  
    extract(shortcode_atts(array(  
		"size" => 'short',
    ), $atts));  
	
	if($size != 'short') { 
		
	}
	
	
	$path = get_template_directory_uri;
	
	// Output
	
	ob_start();
	
	// 
	if($size != 'short') { 
		
	}
	?>


	<ul class="followus">
		<li><a href="http://www.linkedin.com/company/rand-group" target="_blank" title="LinkedIn" class="social-icon linkedin"></a></li>
		<li><a href="https://plus.google.com/106481272899067718943/posts" target="_blank" title="Google Plus" class="social-icon googleplus"></a></li>
		<li><a href="https://twitter.com/rand_group" target="_blank" title="Twitter" class="social-icon twitter"></a></li>
		<li><a href="https://www.facebook.com/randgroupllc" target="_blank" title="Facebook" class="social-icon facebook"></a></li>
		<li><a href="http://www.youtube.com/user/randgroupllc" target="_blank" class="social-icon youtube"></a></li>		
	</ul>


<!-- Original Code

	<ul class="followus">
		<li><a href="http://www.linkedin.com/company/the-rand-group-llc" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/swx-widgets/images/social/linkedin.png'; ?>"></a></li>
		<li><a href="https://plus.google.com/106481272899067718943/posts" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/swx-widgets/images/social/googleplus.png'; ?>"></a></li>
		<li><a href="https://twitter.com/rand_group" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/swx-widgets/images/social/twitter.png'; ?>"></a></li>
		<li><a href="" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/swx-widgets/images/social/youtube.png'; ?>"></a></li>
		<li><a href="https://www.facebook.com/pages/Rand-Group/459648837443005" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/swx-widgets/images/social/facebook.png'; ?>"></a></li>
	</ul>
	
-->	
	
	<?
	$output = ob_get_contents();
	ob_end_clean();
	return $output;	


}  
add_shortcode("followus", "followus");  


?>