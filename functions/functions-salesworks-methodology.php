<?php


/*-----------------------------------------------------------------------------------*/
/*	 Show Salesworks Methodology
/*   Usage: shortcode
/*   Example: [salesworks size=""]
/*-----------------------------------------------------------------------------------*/

function salesworks($atts, $content = null) {  
    extract(shortcode_atts(array(  
		"size" => 'short',
    ), $atts));  
	
	if($size != 'short') { 
		
	}
	
	ob_start();
	?>
	

	<div class="row-fluid salesworks_methodology">
		<div class="span9">
			<p>Rand Group's proprietary <img src="/assets/img/swx-tm-logo.png" class="no-border">&trade; Revenue Generation Methodology addresses your digital marketing challenges with complete B2B services including:</p>
		
				<ul>
					<li><i class=" icon-ok"></i><a href="/services/marketing/"> Marketing Services</a></li>
					<li><i class=" icon-ok"></i><a href="/services/sales/"> Sales Effectiveness</a></li>
					<li><i class=" icon-ok"></i><a href="/solutions/crm-software/"> Customer Relationship Management </a></li>
				</ul>
				<p><a class="btn" href="/about/methodology/salesworks/"> Find Out More</a></p>			
		</div>	
		<div class="span3"><img src="/assets/img/swx-meth.png" class="no-border"></div>
	</div>
						
	<?
	$output = ob_get_contents();
	ob_end_clean();
	return $output;	


}  
add_shortcode("salesworks", "salesworks");  


?>