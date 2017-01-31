<?php
/**
 * The template for displaying audio post format
 */
	global $limoking_post_settings; 
	
	$post_format_data = '';
	$content = trim(get_the_content(esc_html__('Read More', 'limoking')));		
	if(preg_match('#^https?://\S+#', $content, $match)){ 				
		$post_format_data = do_shortcode('[audio src="' . $match[0] . '" ][/audio]');
		$limoking_post_settings['content'] = substr($content, strlen($match[0]));					
	}else if(preg_match('#^\[audio\s.+\[/audio\]#', $content, $match)){ 
		$post_format_data = do_shortcode($match[0]);
		$limoking_post_settings['content'] = substr($content, strlen($match[0]));
	}else{
		$limoking_post_settings['content'] = $content;
	}	

	if ( !empty($post_format_data) ){
		echo '<div class="limoking-blog-thumbnail limoking-audio">' . $post_format_data . '</div>';
	} 
			
			
?>