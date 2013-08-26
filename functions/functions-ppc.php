<?php 

/*-----------------------------------------------------------------------------------*/
/*  PPC Squeeze Page Functions
/*-----------------------------------------------------------------------------------*/

function is_ppc_traffic() { 
	// if k is detected in the URL, eg: http://dev2.salesworks.com/promo/energy-accounting-solutions/?k=test
	if( isset($_GET['k']) || isset($_GET['location'])  ) {
               return true;
	} else { 
               return false; 
	}	
}
// Grab PPC variables

$company = 'NexTec Group';
$product = $_GET['product'];
$location = $_GET['location'];
$kw = $_GET['k'];

if($location == 'ohio') { 
    $location_alias = 'Cleveland';
    $location_city = 'Cleveland';
    $location_state = 'Ohio';
    $location_address1 = '302 N. Cleveland-Massillon Road';
    $location_address2 = 'Akron, OH 44333';
    $location_phone = 'Phone: (330) 598-2400';
    $location_map = '<iframe width="200" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;q=302+N+Cleveland+Massillon+Rd,+Akron,+Summit,+Ohio+44333&amp;aq=&amp;sll=37.0625,-95.677068&amp;sspn=52.020054,77.431641&amp;ie=UTF8&amp;geocode=FRLQcwIdbFAi-w&amp;split=0&amp;hq=&amp;hnear=302+N+Cleveland+Massillon+Rd,+Akron,+Ohio+44333&amp;z=14&amp;ll=41.144338,-81.637268&amp;output=embed"></iframe>';
} elseif ($location == 'nw') { 
    $location_alias = 'Pacific Northwest';
    $location_city = 'Seattle';
    $location_state = 'Washington';
    $location_address1 = '100 W. Harrison St., Suite 460, North Tower';
    $location_address2 = 'Seattle, WA 98119';
    $location_phone = 'Phone: (206) 505-7980';
} elseif ($location == 'washington') { 
    $location_alias = 'Pacific Northwest';
    $location_city = 'Seattle';
    $location_state = 'Washington';
    $location_address1 = '100 W. Harrison St., Suite 460, North Tower';
    $location_address2 = 'Seattle, WA 98119';
    $location_phone = 'Phone: (206) 505-7980';
} elseif ($location == 'seattle') { 
    $location_alias = 'Seattle';
    $location_city = 'Seattle';
    $location_state = 'WA';
    $location_address1 = '100 W. Harrison St., Suite 460, North Tower';
    $location_address2 = 'Seattle, WA 98119';
    $location_phone = 'Phone: (206) 505-7980';
} elseif ($location == 'texas') { 
    $location_alias = 'Houston';
    $location_city = 'Houston';
    $location_state = 'Texas';
    $location_address1 = '1111 North Loop West, Suite 810';
    $location_address2 = 'Houston, TX 77008';
    $location_phone = 'Phone: (713) 957-8350';
} elseif ($location == 'california') { 
    $location_alias = 'SOCAL';
    $location_city = 'Los Angeles';
    $location_state = 'California';
    $location_address1 = '1990 S. Bundy Drive, Suite 520';
    $location_address2 = 'Los Angeles, CA 90025';
    $location_phone = 'Phone: (310) 447-7100';
} elseif ($location == 'new-jersey') { 
    $location_alias = 'New Jersey';
    $location_city = 'Secaucus';
    $location_state = 'New Jersey';
    $location_address1 = '300 Harmon Meadow Blvd., Suite 440';
    $location_address2 = 'Secaucus, NJ 07094';
    $location_phone = 'Phone: (201) 933-0707';
} elseif ($location == 'new-york') { 
    $location_alias = 'NYC';
    $location_city = 'New York';
    $location_state = 'NY';
    $location_address1 = '245 Park Avenue, 39th Floor';
    $location_address2 = 'New York, NY 10167';
    $location_phone = 'Phone: (212) 372-8999';      
} elseif ($location == 'columbus') { 
    $location_alias = 'Central Ohio'; // something that is known to locals
    $location_city = 'Columbus';
    $location_state = 'OH';
    $location_address1 = '110 East Wilson Bridge Road,<br/>Suite 200';
    $location_address2 = 'Worthington, OH 43085';
    $location_phone = 'Phone: Coming Soon';      
} elseif ($location == 'national') { 
    $location_alias = 'North American'; // something that is known to locals
    $location_city = 'Seattle';
    $location_state = 'Washington';
    $location_address1 = '100 W. Harrison St., Suite 460, North Tower';
    $location_address2 = 'Seattle, WA 98119';
    $location_phone = 'Direct: (604) 628-1896';
} else { 
	// catch-all in case location doesn't come through correctly
    $location_alias = 'North American';
    $location_city = 'Seattle';
    $location_state = 'Washington';
    $location_address1 = '100 W. Harrison St., Suite 460, North Tower';
    $location_address2 = 'Seattle, WA 98119';
    $location_phone = 'Direct: (604) 628-1896';
}       
     
function clickPhone($var) {

	echo "<div id='number1' phone='".$var."' id='number1'><span><a href='#' onClick=\"_gaq.push(['_trackEvent', 'PPC', 'Phone Call', '".$var."']);\">Get Phone Number (Click to reveal)</a></span><span style='display: none;'>".$var."</span></div>";
}


/*-----------------------------------------------------------------------------------*/
/* dynamic keyword replacement shortcode 
/*-----------------------------------------------------------------------------------*/
// http://dev2.salesworks.com/shortcodes/
// Usage: [kw]default text[/kw]   
// Usage: [kw case="lower|upper|first"]default text[/kw]   (default is lower if none specified) 
// The default case is all lowercase. However the case can be specified with [ kw case="upper" ]Product A[/ kw ] to capitalize the first character of each word
// or [ kw case="first"]Product a[/ kw ] to only capitalize the first letter of the first word. (Beginning of sentence)

function tz_kw($atts, $content=null) {

	extract(shortcode_atts(array(
	  'case' => 'lower',
	  'pre' => '',
	  'post' => '',	  
	), $atts));

	// determine string
	// $string = '<span style="color:red;">NO DEFAULT CONTENT OR KW PROVIDED ###</span>';
	if($content) $string = $content; 
	if($_GET['k']) $string = $_GET['k']; 

	// determine case
	if($case == 'lower') $string = strtolower($string); // hello world
	if($case == 'upper') $string = ucwords(strtolower($string));  // Hello World
	if($case == 'first') $string = ucfirst(strtolower($string));  // Hello world	

	// add pre or post words as required, but only if there is default copy or a dynamic keyword
	if($content || $_GET['k']) {
		if($pre) $string = $pre.$string; // 
		if($post) $string = $string.$post; // 	
	}
	return $string;

}
add_shortcode('kw', 'tz_kw');


/*-----------------------------------------------------------------------------------*/
/* dynamic location replacement shortcode 
/*-----------------------------------------------------------------------------------*/
// http://dev2.salesworks.com/shortcodes/
// Usage: [location]default location[/location]   
// Usage: [location case="lower|upper|first"]default location[/location]   
// Usage: [location pre="these words first"]default location[/location] = these words first default locaiton
// Usage: [location post="these words last"]default location[/location] = default location these words last

function tz_location($atts, $content=null) {

	extract(shortcode_atts(array(
	  'case' => 'lower',
	  'pre' => '',
	  'post' => '',
	), $atts));

	// determine string
	// $string = '<span style="color:red;">NO DEFAULT CONTENT OR KW PROVIDED ###</span>';
	if($content) $string = $content; 
	if($_GET['location']) {
		// remove hyphen between location
		$string = $_GET['location']; 
		$string = str_replace("-"," ",$string);
		// hacks for some locations
		if($string == 'nw') $string = 'the Northwest';
		if($string == 'national') return;
		
	}
	

	// determine case 
	if($case == 'lower') $string = strtolower($string); // hello world
	if($case == 'upper') $string = ucwords(strtolower($string));  // Hello World
	if($case == 'first') $string = ucfirst(strtolower($string));  // Hello world	

	// add pre or post words as required, but only if there is default copy or a dynamic keyword
	if($content || $_GET['location']) {
		if($pre) $string = $pre.$string; // 
		if($post) $string = $string.$post; // 	
	}

	
	return $string;

}
add_shortcode('location', 'tz_location');

?>