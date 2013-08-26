<?php

/*-----------------------------------------------------------------------------------*/
/*	 Micro Functions
/*-----------------------------------------------------------------------------------*/
/* Get ID/Name/Slug of parent category 
* returns Array ( [0] => 249 [1] => sql-server [2] => SQL Server )
*/
function get_parent_id($array) {
		if(isset($array[0])) { 
			// get 1st category
			$cat_id = $array[0];		
			// get parent category
			$cat_family = explode(',',get_category_parents($cat_id,false,',')); 
			$cat_parent_name = $cat_family[0];
			// debug 
			/*
				echo '0: '.$cat_family[0];
				echo '1: '.$cat_family[1];
				echo '2: '.$cat_family[2];
			*/
			// get ID of parent
			$idObj= get_term_by('name', $cat_parent_name, 'category');
			$cat_parent_id = $idObj->term_id;
			$cat_parent_slug = $idObj->slug;	
			// build array
			$results = array($cat_parent_id,$cat_parent_slug,$cat_parent_name);
			
					
		
			return $results;		
			
			}
		else
			{ 
				return 0; 
			} 
}
/* Get ID/Name/Slug of parent category 
* returns Array ( [0] => 249 [1] => sql-server [2] => SQL Server )
*/
function get_parent_name($cat_id,$option = null, $return = null) { 
		if(isset($cat_id)) { 			
			// get parent category
			$cat_family = explode(',',get_category_parents($cat_id,false,',')); 
			$cat_parent_name = $cat_family[0];
			// debug 
			/*
				echo '0: '.$cat_family[0];
				echo '1: '.$cat_family[1];
				echo '2: '.$cat_family[2];
			*/
			// get ID of parent
			$idObj= get_term_by('name', $cat_parent_name, 'category');
			$cat_parent_id = $idObj->term_id;
			$cat_parent_slug = $idObj->slug;	
			// build array
			$results = array($cat_parent_id,$cat_parent_slug,$cat_parent_name);
			
			if($return == 'true') {
				return $results[$option]; ;
				}
			else {			
				echo $results[$option]; 
				}
			}
		else
			{ 
				return 0; 
			} 
}

/*-----------------------------------------------------------------------------------*/
/*  Neatly trim a string without cutting off a word
/*  http://www.justin-cook.com/wp/2006/06/27/php-trim-a-string-without-cutting-any-words/
/*-----------------------------------------------------------------------------------*/
/**
 * Cut string to n symbols and add delim but do not break words.
 *
 * Example:
 * <code>
 *  $string = 'this sentence is way too long';
 *  echo neat_trim($string, 16);
 * </code>
 *
 * Output: 'this sentence is...'
 *
 * @access public
 * @param string string we are operating with
 * @param integer character count to cut to
 * @param string|NULL delimiter. Default: '...'
 * @return string processed string
 **/
function neat_trim($str, $n, $delim='...') {
   $len = strlen($str);
   if ($len > $n) {
       preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
       return rtrim($matches[1]) . $delim;
   }
   else {
       return $str;
   }
}
function neat_trim_code($str, $n, $delim='...') {
   $len = strlen($str);
   if ($len > $n) {
       $str = strip_tags($str);
	   $offset = 0;
		while($offset = strpos($str, " ", $offset + 1)) {
			if ($offset > $n) break;
		}
	   //preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
       return substr($str, 0, $offset) . $delim;
   }
   else {
       return $str;
   }
}

/*-----------------------------------------------------------------------------------*/
/*  List x subpages from y parent page
/*  // http://wordpress.org/support/topic/limit-number-of-pages-shown-from-of-wp_list_pages-and-a-read-more-link?replies=5
/*-----------------------------------------------------------------------------------*/
// usage:
//  list_subs(3, 323);
function list_subs($x, $y) {
				
	$howmany = $x;
	$child = $y;
	$pages = wp_list_pages("echo=0&title_li=&child_of=".$child."&sort_column=menu_order&depth=1");
	$pages_arr = explode("\n", $pages);
	for($i=0;$i<$howmany;$i++){
		echo $pages_arr[$i];
	}
} 


/*-----------------------------------------------------------------------------------*/
/*  Check if a Page is a Child of Another Page in WordPress or the parent page
/*  http://bavotasan.com/2009/check-if-a-page-is-a-child-of-another-page-in-wordpress/
/*-----------------------------------------------------------------------------------*/
function is_child($pageID) { 
	global $post; 
	if( is_page() && ($post->post_parent==$pageID) ) {
               return true;
	} else { 
               return false; 
	}
}
// to target only children, and not parent
function is_only_child($pageID) { 
	global $post; 
	if( $post->post_parent==$pageID ) {
               return true;
	} else { 
               return false; 
	}
}


/*-----------------------------------------------------------------------------------*/
/* wmv silverlight player
/*-----------------------------------------------------------------------------------*/
// http://dev2.salesworks.com/shortcodes/
// Usage: [wmv]http://dev2.salesworks.com/wp-content/uploads/videos/2011-12-21%2009.01%20MAS%20500%20v7.4%20Webcast.wmv[/wmv]   
// Usage: [wmv width='222' height='111' image='/wp-content/uploads/123.png']/wp-content/uploads/videos/2011-12-21%2009.01%20MAS%20500%20v7.4%20Webcast.wmv[/wmv]   

function swx_wmv($atts, $content=null) {

	extract(shortcode_atts(array(
	  'width' => '500',
	  'height' => '281',
	  'image' => '/wp-content/themes/repro/wmvplayer/nextec_play_video.png'
	), $atts));
	
	
	$output = "
	<div name='mediaspace' id='mediaspace'></div>
		<script type='text/javascript' src='/wp-content/themes/repro/wmvplayer/silverlight.js'></script>
		<script type='text/javascript' src='/wp-content/themes/repro/wmvplayer/wmvplayer.js'></script>
		<script type='text/javascript'>
			var cnt = document.getElementById('mediaspace');
			var src = '/wp-content/themes/repro/wmvplayer/wmvplayer.xaml';
			var cfg = {
				file:'".$content."',
				image:'".$image."',
				height:'".$height."',
				width:'".$width."'
			};
			var ply = new jeroenwijering.Player(cnt,src,cfg);
		</script>";
	
	return $output;
	
}
add_shortcode('wmv', 'swx_wmv');


/*-----------------------------------------------------------------------------------*/
/* Make shortcodes active in post/page titles
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_title', 'do_shortcode' );

/*-----------------------------------------------------------------------------------*/
/*	Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/

add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');


/*-----------------------------------------------------------------------------------*/
/* Check if string contains array of search words
/* Example: 
/* $comment = 'billie jean is not my lover she is just a girl';
/* $words = array('jean','lover','jean');
/* echo contains($comment, $words); 
/* returns 1
/* http://stackoverflow.com/questions/6228581/how-to-search-array-of-string-in-another-string-in-php
/*-----------------------------------------------------------------------------------*/
function contains( $string, array $search, $caseInsensitive=false ){
	$exp = '/'.implode('|',array_map('preg_quote',$search)).($caseInsensitive?'/i':'/');
	return preg_match($exp, $string)?true:false;
}


/*-----------------------------------------------------------------------------------*/
/*  Is Custom Post Type Conditional (http://wordpress.stackexchange.com/questions/6731/if-is-custom-post-type) 
/*-----------------------------------------------------------------------------------*/
function is_post_type($type){
    global $wp_query;
    if($type == get_post_type($wp_query->post->ID)) return true;
    return false;
}



/*-----------------------------------------------------------------------------------*/
/*	 Publish the content in the feed later
/*   http://wpengineer.com/320/publish-the-feed-later/
/*   Usage: Widget
/*-----------------------------------------------------------------------------------*/
function publish_later_on_feed($where) {
    global $wpdb;
 
    if ( is_feed() ) {
        // timestamp in WP-format
        $now = gmdate('Y-m-d H:i:s');
 
        // value for wait; + device
		// default is 5 min, using 24 hrs
        $wait = '5'; // integer
 
        // http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_timestampdiff
        $device = 'MINUTE'; //MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
 
        // add SQL-sytax to default $where
        $where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
    }
    return $where;
}
 
add_filter('posts_where', 'publish_later_on_feed');


/*-----------------------------------------------------------------------------------*/
/*	 Load different template for sub category
/*-----------------------------------------------------------------------------------*/
function sub_category_template() { 
    
    // Get the category id from global query variables
    $cat = get_query_var('cat');

    if(!empty($cat)) {    
        
		// Specify the ID's of your parent (1st level) categories) 
		$parent_cats = array('2','3','4','5','42','48');		
		
        // If the current category isn't in the parent list, and if the unique template exists
        if( ( ! in_array($cat, $parent_cats) ) && (file_exists(TEMPLATEPATH . '/sub-category-template.php')) ) { 
            
            // Include the template for sub-catgeory
            include(TEMPLATEPATH . '/sub-category-template.php');
            exit;
        }
        return;
    }
    return;

}
add_action('template_redirect', 'sub_category_template');

/*-----------------------------------------------------------------------------------*/
/*	 Test If In Loop
/*-----------------------------------------------------------------------------------*/
function is_loop() { 
 global $wp_the_query, $wp_query, $post, $wp; 
 if ($wp_the_query === $wp_query) :  
 return TRUE;
 endif; 
 return FALSE;
} 



?>