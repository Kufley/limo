<?php
/**
 * The template for displaying image post format
 */
	global $limoking_post_settings; 

	$post_format_data = '';
	$content = trim(get_the_content(esc_html__( 'Read More', 'limoking' )));
	if(preg_match('#^<a.+<img.+/></a>|^<img.+/>#', $content, $match)){ 
		$post_format_data = $match[0];
		$limoking_post_settings['content'] = substr($content, strlen($match[0]));
	}else if(preg_match('#^https?://\S+#', $content, $match)){
		$post_format_data = limoking_get_image($match[0], 'full', true);
		$limoking_post_settings['content'] = substr($content, strlen($match[0]));					
	}else{
		$limoking_post_settings['content'] = $content;
	}
	
	if ( !empty($post_format_data) ){
		echo '<div class="limoking-blog-thumbnail">';
		echo limoking_escape_content($post_format_data); 
		
		if( !is_single() && is_sticky() ){
			echo '<div class="limoking-sticky-banner">';
			echo '<i class="fa ' . limoking_fa_class('icon-bullhorn') . '" ></i>';
			echo esc_html__('Sticky Post', 'limoking');
			echo '</div>';
		}					
		echo '</div>';
	} 
	?>