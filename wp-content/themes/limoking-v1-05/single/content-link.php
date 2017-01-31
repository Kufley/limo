<?php
/**
 * The template for displaying link post format
 */

	if( !is_single() ){ 
		global $limoking_post_settings; 
		if($limoking_post_settings['excerpt'] < 0) global $more; $more = 0;
	}	

	$post_format_data = '';
	$content = trim(get_the_content(esc_html__( 'Read More', 'limoking' )));
	if(preg_match('#^<a.+href=[\'"]([^\'"]+).+</a>#', $content, $match)){ 
		$post_format_data = $match[1];
		$content = substr($content, strlen($match[0]));
	}else if(preg_match('#^https?://\S+#', $content, $match)){
		$post_format_data = $match[0];
		$content = substr($content, strlen($match[0]));
	}	
?>
	
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-header" >
		<?php 
			// print blog information
			if( is_single() ){
				echo limoking_get_blog_info(array('author', 'date', 'category', 'comment')); 
			}
		
			// blog title
			$blog_link = empty($post_format_data)? get_the_title(): $post_format_data;
			if( is_single() ){
				echo '<h1 class="limoking-blog-title"><a href="' . $blog_link . '" >' . get_the_title() . '</a></h1>';
			}else{
				echo '<h3 class="limoking-blog-title"><a href="' . $blog_link . '" >' . get_the_title() . '</a></h3>';
			}
			
			// print blog information for widget style
			if( !is_single() && !empty($limoking_post_settings['blog-info-widget']) ){
				echo limoking_get_blog_info($limoking_post_settings['blog-info']);
			}	
				
		?>
		<div class="clear"></div>	
	</header>
	<?php 
		if( is_single() || $limoking_post_settings['excerpt'] < 0 ){
			echo '<div class="limoking-blog-content">';
			echo limoking_content_filter($content, true);
			wp_link_pages( array( 
				'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'limoking' ) . '</span>', 
				'after' => '</div>', 
				'link_before' => '<span>', 
				'link_after' => '</span>' )
			);
			echo '</div>';
		}else if($limoking_post_settings['excerpt'] > 0){
			echo '<div class="limoking-blog-content">' . get_the_excerpt() . '</div>';
		}
	?>	
</article>