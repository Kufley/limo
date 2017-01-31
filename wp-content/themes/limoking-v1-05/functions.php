<?php
	/*	
	*	Goodlayers Function File
	*	---------------------------------------------------------------------
	*	This file include all of important function and features of the theme
	*	---------------------------------------------------------------------
	*/
	
	////// DO NOT REMOVE OR MODIFY THIS /////
	define('WP_THEME_KEY', 'goodlayers');  //
	/////////////////////////////////////////
	
	define('THEME_FULL_NAME', 'Limo King');
	define('THEME_SHORT_NAME', 'lmkg');
	define('THEME_SLUG', 'limoking');
	
	global $sitepress;
	if( !empty($sitepress) ){
		define('AJAX_URL', admin_url('admin-ajax.php?lang=' . $sitepress->get_current_language()));
	}else{
		define('AJAX_URL', admin_url('admin-ajax.php'));
	}
	
	if ( is_ssl() ) {
		define('HTTP_TYPE', 'https://');
	}else{
		define('HTTP_TYPE', 'http://');
	}
	
	$limoking_gallery_id = 0;
	$limoking_lightbox_id = 0;
	$limoking_crop_video = false;
	$limoking_excerpt_length = 55;
	$limoking_excerpt_read_more = true;

	$limoking_spaces = array(
		'top-wrapper' => '60px', 
		'bottom-wrapper'=>'40px', 
		'top-full-wrapper' => '0px', 
		'bottom-item' => '20px',
		'bottom-blog-item' => '0px',
		'bottom-divider-item' => '50px'
	);
	
	$theme_option = get_option(THEME_SHORT_NAME . '_admin_option', array());
	$theme_option['content-width'] = 960;
	
	// include goodlayers framework
	include_once(get_template_directory() . '/framework/gdlr-framework.php' );
	
	//-------------------------- theme section ---------------------------//

	// create sidebar controller
	$limoking_sidebar_controller = new limoking_sidebar_generator();	
	
	// create font controller
	if( empty($theme_option['upload-font']) ){ $theme_option['upload-font'] = ''; }
	$limoking_font_controller = new limoking_font_loader( json_decode($theme_option['upload-font'], true) );	
	
	// create navigation controller
	if( empty($theme_option['enable-goodlayers-navigation']) || $theme_option['enable-goodlayers-navigation'] != 'disable'){
		include_once(get_template_directory() . '/include/gdlr-navigation-menu.php');
	}	
	if( empty($theme_option['enable-goodlayers-mobile-navigation']) || $theme_option['enable-goodlayers-mobile-navigation'] != 'disable'){
		include_once(get_template_directory() . '/include/gdlr-responsive-menu.php');
	}
	
	// utility function
	include_once(get_template_directory() . '/include/function/gdlr-media.php');
	include_once(get_template_directory() . '/include/function/gdlr-utility.php');		
	include_once(get_template_directory() . '/include/function/gdlr-file-system.php');		

	// register function / filter / action
	include_once(get_template_directory() . '/functions-size.php');	
	include_once(get_template_directory() . '/include/gdlr-include-script.php');	
	include_once(get_template_directory() . '/include/function/gdlr-function-regist.php');	
	
	// create admin option
	include_once(get_template_directory() . '/include/gdlr-admin-option.php');
	include_once(get_template_directory() . '/include/gdlr-plugin-option.php');
	include_once(get_template_directory() . '/include/gdlr-font-controls.php');
	include_once(get_template_directory() . '/include/gdlr-social-icon.php');

	// create page options
	include_once(get_template_directory() . '/include/gdlr-page-options.php');
	include_once(get_template_directory() . '/include/gdlr-demo-page.php');
	include_once(get_template_directory() . '/include/gdlr-post-options.php');
	
	// create page builder
	include_once(get_template_directory() . '/include/gdlr-page-builder-option.php');
	include_once(get_template_directory() . '/include/function/gdlr-page-builder.php');
	
	include_once(get_template_directory() . '/include/function/gdlr-page-item.php');
	include_once(get_template_directory() . '/include/function/gdlr-blog-item.php');
	
	// widget
	include_once(get_template_directory() . '/include/widget/recent-comment.php');
	include_once(get_template_directory() . '/include/widget/recent-post-widget.php');
	include_once(get_template_directory() . '/include/widget/popular-post-widget.php');
	include_once(get_template_directory() . '/include/widget/post-slider-widget.php');	
	include_once(get_template_directory() . '/include/widget/recent-port-widget.php');
	include_once(get_template_directory() . '/include/widget/recent-port-widget-2.php');
	include_once(get_template_directory() . '/include/widget/port-slider-widget.php');
	include_once(get_template_directory() . '/include/widget/flickr-widget.php');
	include_once(get_template_directory() . '/include/widget/video-widget.php');
	
	// plugin support
	include_once(get_template_directory() . '/plugins/wpml.php');
	include_once(get_template_directory() . '/plugins/layerslider.php');
	include_once(get_template_directory() . '/plugins/masterslider.php');
	include_once(get_template_directory() . '/plugins/woocommerce.php');
	include_once(get_template_directory() . '/plugins/goodlayers-importer.php');
	
	if( empty($theme_option['enable-plugin-recommendation']) || $theme_option['enable-plugin-recommendation'] == 'enable' ){
		include_once(get_template_directory() . '/include/plugin/gdlr-plugin-activation.php');
	}

	// init include script class
	if( !is_admin() ){ new limoking_include_script(); }	
	
	// revision
	include_once(get_template_directory() . '/gdlr-revision.php');
	
?>