<?php

/*-----------------------------------------------------------------------------------*/
/*	Add custom post type for Squeeze Pages
/*-----------------------------------------------------------------------------------*/
add_action('init', 'squeeze_register');
function squeeze_register() {
	$args = array(
		'label' => __('Squeeze Pages'),
		'singular_label' => __('Squeeze Page'),
		'public' => true,
		'show_ui' => true,
		'query_var' => true,
		'capability_type' => 'page',
		'hierarchical' => false,
		'menu_icon' => plugins_url( '/images/icons/funnel.png', dirname(__FILE__) ), 
		'rewrite' => array('with_front' => false, 'slug' => 'p'),
		'has_archive' => true,		
		'supports' => array('title', 'editor', 'thumbnail')
	);
	register_post_type( 'promo' , $args );
}

?>