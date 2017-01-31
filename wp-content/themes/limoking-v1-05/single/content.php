<?php
	if( in_array(get_post_format(), array('aside', 'link', 'quote')) ){
		get_template_part('single/content', get_post_format());
	}else{
		
		global $limoking_post_settings;
		if( empty($limoking_post_settings['blog-style']) || $limoking_post_settings['blog-style'] == 'blog-full' ){
			get_template_part('single/content-full');
		}else if( $limoking_post_settings['blog-style'] == 'blog-medium' ){
			get_template_part('single/content-medium');
		}else if( strpos($limoking_post_settings['blog-style'], 'blog-widget') !== false ){
			get_template_part('single/content-widget');
		}else if( strpos($limoking_post_settings['blog-style'], 'blog-') !== false ){
			get_template_part('single/content-grid');
		}else if( is_single() ){
			get_template_part('single/content-single');	
		}
		
	} 
?>