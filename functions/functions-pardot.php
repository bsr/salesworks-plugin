<?php


/*	 Pardot Forms
#################################################################################### */


/*-----------------------------------------------------------------------------------*/
/*	 Enqueue the required utmz, geoip and augment script
/*   Usage: head
/*-----------------------------------------------------------------------------------*/

/*
    <!-- augment data -->
    <script type="text/javascript" src="http://j.maxmind.com/app/geoip.js"></script> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script> 
    <script type="text/javascript" src="http://www.randgroup.com/wp-content/plugins/swx-widgets/js/utmz.js"></script> 
    <script type="text/javascript" src="http://www.randgroup.com/wp-content/plugins/swx-widgets/js/augment.js"></script> 
*/

add_action('wp_footer', 'pardot_scripts');

function pardot_scripts() {
    $echo = '<script type="text/javascript" src="http://j.maxmind.com/app/geoip.js"></script> 
    <script type="text/javascript" src="http://www.randgroup.com/wp-content/plugins/swx-widgets/js/utmz.js"></script> 
    <script type="text/javascript" src="http://www.randgroup.com/wp-content/plugins/swx-widgets/js/augment.js"></script> ';

		echo $echo;
}

/*
function load_pardot_scripts() {  

	wp_register_script('geoip', 'http://j.maxmind.com/app/geoip.js', array('jquery'));
	wp_enqueue_script('geoip',array(), 'version', true ); // moves to footer, see http://blog.cloudfour.com/getting-all-javascript-into-the-footer-in-wordpress-not-so-fast-buster/
	//wp_register_script('utmz', 'http://www.dynamics101.com/wp-content/plugins/utmz-geoip/js/utmz.js', array('jquery'));
	wp_register_script('utmz', plugins_url('/js/utmz.js', dirname(__FILE__) ), array('jquery'));
	wp_enqueue_script('utmz',array(), 'version', true );
	//wp_register_script('augment', 'http://www.dynamics101.com/wp-content/plugins/utmz-geoip/js/augment.js', array('jquery'));
	wp_register_script('augment', plugins_url('/js/augment.js', dirname(__FILE__) ), array('jquery'));
	wp_enqueue_script('augment',array(), 'version', true );

}  

add_action('wp_enqueue_scripts', 'load_pardot_scripts');  */



 
/*-----------------------------------------------------------------------------------*/
/*	 Show Iframe Forms
/*   http://www.pardot.com/faqs/forms/populate-hidden-field-on-form-with-name-of-webpage/
/*   Usage: shortcode
/*   Example: [pardot form_url="http://www2.dynamics101.com/l/20752/2013-02-25/68q" height="505" width="505"]
/*-----------------------------------------------------------------------------------*/

function pardot($atts, $content = null) {  
    extract(shortcode_atts(array(  
        "form_url" => 'http://www2.randgroup.com/l/20752/2013-02-25/68q',
		"return_url" => null,
		"width" => '500',
		"height" => '500',
		"size" => 'short',
		"redirect_to" => null,
		"offer_name" => null,
		"offer_category" => null,
		"id" => 'pardotForm'
    ), $atts));  
	
	if($size != 'short') { 
		
	}
	
	if(strlen($redirect_to) > 0) {
		$redirect_to = urlencode($redirect_to);
		$redirect_to = 'redirect_to='.$redirect_to;			
	} elseif ($_GET['redirect_to']) {
		$redirect_to = $_GET['redirect_to'];
		$redirect_to = 'http://'.$_SERVER['HTTP_HOST'].$redirect_to;
		$redirect_to = urlencode($redirect_to);
		$redirect_to = 'redirect_to='.$redirect_to;
	} elseif ($_GET['did']) {
		$did = $_GET['did'];
		$redirect_to = '/resource-download/'.$did;
		$redirect_to = 'http://'.$_SERVER['HTTP_HOST'].$redirect_to;
		$redirect_to = urlencode($redirect_to);
		$redirect_to = 'redirect_to='.$redirect_to;
	
	}
	
	if(strlen($offer_name) > 0) {
		$offer_name = '&offer_name='.urlencode($offer_name);
		$redirect_to .= $offer_name;
	}
	
	if(strlen($offer_category) > 0) {
		$offer_category = '&offer_category='.urlencode($offer_category);
		$redirect_to .= $offer_category;
	}
	
	
	
    return '<iframe src="'.$form_url.'?'.$redirect_to.'" url="'.$form_url.'" width="'.$width.'" height="'.$height.'" id="'.$id.'" redirect_to="'.$redirect_to.'" type="text/html" frameborder="0" allowTransparency="true" style="border: 0"></iframe>';

}  
add_shortcode("pardot", "pardot");  


?>