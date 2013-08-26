<?php 

// ########################################################################
// Dynamically pull resources via Download Monitor plugin based on tags 
// (which are set within Download Monitor) that match the slug (permalink) 
// of the page the visitor is on. 
// ########################################################################
// config
// basename returns the trailing section of the url
// eg: /solutions/gp/training/ it will return "training"


// wait until all plugins are loaded before loading this
function init_my_plugin()
{
	// do init stuff
	if (!function_exists('get_downloads')) { 
		return;
		
	} else { 

	
		/* [list_downloads] */
		function list_downloads($atts, $content = null) {  
			extract(shortcode_atts(array(  
				"tag" => 'all'
			), $atts));  
			

			$catname = basename(get_permalink());
			// match url to catid array
			
			$cat_list = array (  
			
				 "3" => "microsoft-dynamics-ax", 
				 "4" => "microsoft-dynamics-nav", 
				 "5" => "microsoft-dynamics-gp", 
				 "6" => "microsoft-dynamics-crm", 
				 "23" => "project-trax", 
				 "7" => "microsoft-sharepoint", 
				 "8" => "sql-server", 

				 "10" => "upstream-oil-gas-software", 
				 "11" => "manufacturing-software", 
				 "12" => "distribution-software", 
				 "13" => "construction-software", 
				 "14" => "professional-services-software", 
				 
			); 
			
			$catid = array_search($catname, $cat_list);
			// debug 
			// echo $catname;
			// echo $catid;
			// don't forget, [list_downloads] required on page
			// only assets that match tags will show up
			
			if($catid > 0) { 

				$orderby = 'date';
				$qty = 3;
				$char_count = 105;
				$char_count_video = 30;
				$path = get_bloginfo('template_directory');
				$title = get_the_title().' ';
					
				$dl = get_downloads('limit='.$qty.'&category='.$catid.'&tags=demo,case study,whitepaper,brochure&order=ASC&orderby='.$orderby);
				if (!empty($dl)) {
				
					// build and process
					$dl_demo = get_downloads('limit='.$qty.'&category='.$catid.'&tags=demo&order=ASC&orderby='.$orderby);
					$dl_whitepaper = get_downloads('limit='.$qty.'&category='.$catid.'&tags=whitepaper&order=ASC&orderby='.$orderby);
					$dl_casestudy = get_downloads('limit='.$qty.'&category='.$catid.'&tags=case study&order=ASC&orderby='.$orderby);
					$dl_brochure = get_downloads('limit='.$qty.'&category='.$catid.'&tags=brochure&order=ASC&orderby='.$orderby);
					
					// check if all 4 have results, adjust span size accordingly
					
					// output
					$buff = '<div class="download_list">';
					$buff .= '<h3>Related Resources</h3>';
					// check if individual categories contain resources, if so show subnav
					$i = 0;
					// demos
					if (!empty($dl_demo)) {						
						$buff .= '<h4>'.$title.'Demos</h4>';
						foreach($dl_demo as $d) { 
							$i++;
							//if this is first value in row, create new row
							if($i % 3 == 1) {
								$buff .= '<div class="row-fluid">';
							}
							$buff .= '<div class="span4 clearfix"><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink downloadVideo download'.$d->id.'">
							<img src="'.$d->thumbnail.'" align="left" alt="'.$d->title.'" />
							<h5>'.neat_trim($d->title,$char_count_video).'</h5>
							</a></div>';
							//if this is third value in row, end row
							if($i % 3 == 0) {
								$buff .= '</div>';
							}
							// $buff .= $i;
						}
						//if the counter is not divisible by 3, we have an open row
						$spacercells = 3 - ($i % 3);
						if ($spacercells < 3) {							
							$buff .= "</div>";
						}
						
					}
					$i = 0;
					// white papers
					if (!empty($dl_whitepaper)) {
						$buff .= '<h4>'.$title.'Whitepapers</h4>';
						foreach($dl_whitepaper as $d) { 
							$i++;
							//if this is first value in row, create new row
							if($i % 3 == 1) {
								$buff .= '<div class="row-fluid">';
							}
							$buff .= '<div class="span4 clearfix"><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'">
							<img src="'.$d->thumbnail.'" align="left" alt="'.$d->title.'" />
							<h5>'.neat_trim($d->title,$char_count).'</h5>
							</a></div>';
							//if this is third value in row, end row
							if($i % 3 == 0) {
								$buff .= '</div>';
							}
							// $buff .= $i;
						}
						//if the counter is not divisible by 3, we have an open row
						$spacercells = 3 - ($i % 3);
						if ($spacercells < 3) {							
							$buff .= "</div>";
						}
					}
					$i = 0;
					// case studies
					if (!empty($dl_casestudy)) {
						$buff .= '<h4>'.$title.'Case Studies</h4>';
						foreach($dl_casestudy as $d) { 
							$i++;
							//if this is first value in row, create new row
							if($i % 3 == 1) {
								$buff .= '<div class="row-fluid">';
							}
							$buff .= '<div class="span4 clearfix"><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'">
							<img src="'.$d->thumbnail.'" align="left" alt="'.$d->title.'" />
							<h5>'.neat_trim($d->title,$char_count).'</h5>
							</a></div>';
							//if this is third value in row, end row
							if($i % 3 == 0) {
								$buff .= '</div>';
							}
							// $buff .= $i;
						}
						//if the counter is not divisible by 3, we have an open row
						$spacercells = 3 - ($i % 3);
						if ($spacercells < 3) {							
							$buff .= "</div>";
						}
					}
					$i = 0;
					// Brochure
					if (!empty($dl_brochure)) {
						$buff .= '<h4>'.$title.'Brochures</h4>';
						foreach($dl_brochure as $d) { 
							$i++;
							//if this is first value in row, create new row
							if($i % 3 == 1) {
								$buff .= '<div class="row-fluid">';
							}
							$buff .= '<div class="span4 clearfix"><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'">
							<img src="'.$d->thumbnail.'" align="left" alt="'.$d->title.'" />
							<h5>'.neat_trim($d->title,$char_count).'</h5>
							</a></div>';
							//if this is third value in row, end row
							if($i % 3 == 0) {
								$buff .= '</div>';
							}
							// $buff .= $i;
						}
						//if the counter is not divisible by 3, we have an open row
						$spacercells = 3 - ($i % 3);
						if ($spacercells < 3) {							
							$buff .= "</div>";
						}
					}

				$buff .= '</div>';

				// collect buffer
				return $buff;
				} 
			}
		}
		add_shortcode("list_downloads", "list_downloads");  

		/* [list_download] for individual downloads */
		function list_download($atts, $content = null) {  
			extract(shortcode_atts(array(  
				"id" => null,
				"headline" => null,
				"verb" => 'Download',
				"tag" => 'all'
			), $atts)); 	
			
			$char_count = 135;
			$format_url = 7;
			$format_image = 6;
			$format_title = 8;
			$format_description = 10;
			$format_count = 9;
			$path = get_bloginfo('template_directory');

			$buff .= '<div class="row-fluid download_cta">';
			$buff .= '	<div class="span2">';
			$buff .= '		<a href="'.do_shortcode('[download id="'.$id.'" format="'.$format_url.'"]').'" class="downloadLink">'.do_shortcode('[download id="'.$id.'" format="'.$format_image.'"]').'</a>';
			$buff .= '	</div>';
			$buff .= '	<div class="span10">';
			$buff .= '		<h3>'.$headline.'</h3>';
			// $buff .= '		<h5>'.do_shortcode('[download id="'.$id.'" format="'.$format_title.'"]').'</h5>';
			$buff .= '		<p class="description">'.neat_trim_code(do_shortcode('[download id="'.$id.'" format="'.$format_description.'"]'),$char_count).'</p>';
			//$buff .= '		<p class="description">'.do_shortcode('[download id="'.$id.'" format="'.$format_description.'"]').'</p>';
			// $buff .= '		<p class="meta">'.do_shortcode('[download id="'.$id.'" format="'.$format_count.'"]').'</p>';
			$buff .= '		<a href="'.do_shortcode('[download id="'.$id.'" format="'.$format_url.'"]').'" class="btn">'.$verb.' "'.do_shortcode('[download id="'.$id.'" format="'.$format_title.'"]').'"</a>';
			
			
			$buff .= '	</div>';
			$buff .= '</div>';

			
			return $buff;
			
		}
		add_shortcode("list_download", "list_download");  

		
	}
}
add_action('plugins_loaded','init_my_plugin');


/* 

widget code 


		// output
				$buff = '<div class="widget"><div class="wrap">';
				$buff .= '<h4><span>Related Resources</span></h4>';
				$buff .= '<div class="textwidget">';
				$buff .= '<ul class="dlm_download_list">';
				// check if individual categories contain resources, if so show subnav
				// white papers
				$dl = get_downloads('limit='.$qty.'&category=3&tags='.$tag.'&order=ASC&orderby='.$orderby);
				if (!empty($dl)) {
					$buff .= '<li><h5>Whitepapers</h5>';
					$buff .= '<ul>';
					foreach($dl as $d) { 
						$buff .= '<h5><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'"><img src="'.$d->thumbnail.'" alt="'.$d->title.'" />'.$d->title.'</a></h5>';
						}
					$buff .= '</ul></li>';
				}
				// case studies
				$dl = get_downloads('limit='.$qty.'&category=2&tags=case study&order=ASC&orderby='.$orderby);
				if (!empty($dl)) {
					$buff .= '<li><h5>Case Studies</h5>';
					$buff .= '<ul>';
					foreach($dl as $d) {
						$path = get_bloginfo('template_directory');
						$buff .= '<h5><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'"><img src="'.$d->thumbnail.'" alt="'.$d->title.'" />'.$d->title.'</a></h5>';
						}
					$buff .= '</ul></li>';
				}
				// Brochure
				$dl = get_downloads('limit='.$qty.'&category=4&tags=brochure&order=ASC&orderby='.$orderby);
				if (!empty($dl)) {
					$buff .= '<li><h5>Brochures</h5>';
					$buff .= '<ul>';
					foreach($dl as $d) {
						$path = get_bloginfo('template_directory');
						$buff .= '<h5><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'"><img src="'.$d->thumbnail.'" alt="'.$d->title.'" />'.$d->title.'</a></h5>';
						}
					$buff .= '</ul></li>';
				}
				// demos
				$dl = get_downloads('limit='.$qty.'&category=5&tags=demo&order=ASC&orderby='.$orderby);
				if (!empty($dl)) {
					$buff .= '<li><h5>Demos</h5>';
					$buff .= '<ul>';
					foreach($dl as $d) {
						$path = get_bloginfo('template_directory');
						$buff .= '<h5><a href="/resources/?did='.$d->id.'" title="'.$d->title.'" class="downloadLink download'.$d->id.'"><img src="'.$d->thumbnail.'" alt="'.$d->title.'" />'.$d->title.'</a></h5>';
						}
					$buff .= '</ul></li>';
				}

			$buff .= '<br class="clear" />';
			$buff .= '</ul>';        
			$buff .= '</div>';
			$buff .= '</div></div><!--widget-->';   
			
*/

/*-----------------------------------------------------------------------------------*/
/*	 WP Download Monitor Hacks
/*   http://wordpress.org/support/topic/seo-titles-for-download-monitor-file-and-category-pages
/*   Usage: Title rewrite
/*-----------------------------------------------------------------------------------*/


// SEO titles for Download Monitor pages
function dm_seo_title_tag($title) {
	// Set the separator for our title tag
	global $sep;
	if ( !isset( $sep ) || empty( $sep ) )
		$sep = '-';
	// SEO titles for Download Monitor single file pages
	// Check that the "did" (download ID) variable is set, valid, and that the get_downloads function exists
	if (isset($_GET['did']) && is_numeric($_GET['did']) && $_GET['did']>0 && function_exists(get_downloads)) {
		$did = $_GET['did'];
		// Grab the file info and, if non-empty, adjust the title tag accordingly
		$dl = get_downloads('limit=1&include='.$did);
		if (!empty($dl)) {
			foreach($dl as $d) {
				$title = $d->title.' '.$sep.' '.$title;
			}
		}
	}
	// SEO titles for Download Monitor category pages
	elseif (isset($_GET['category'])) {
		$catID = $_GET['category'];
		// First need to get category name using $catID
		global $wpdb, $wp_dlm_db_taxonomies;
		if (isset($wp_dlm_db_taxonomies)) {
			$cat = $wpdb->get_var( "SELECT name FROM $wp_dlm_db_taxonomies WHERE id = $catID;" );
			// Find out if we're on a paginated page (but not page 1), and if so, set the variable
			if (isset($_GET['dlpage']) && is_numeric($_GET['dlpage']) && $_GET['dlpage'] != 1) $dlpage = $_GET['dlpage'];
			// Then we can tack the category name and, if set, the pagination page, onto the title
			$oldTitle = $title;
			$title = $cat;
			if ($dlpage) $title .= ' (Page '.$dlpage.') ';
			$title .= ' '.$sep.' '.$oldTitle;
		}
	}
	return $title;
}

// We give this a low priority so that it springs into action after any other SEO plugins have played with the title tag
add_filter('wp_title', 'dm_seo_title_tag', 100);

?>