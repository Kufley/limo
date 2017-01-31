<?php
/**
 * The template for displaying posts in the Aside post format
 */
 
	if( !is_single() ){ 
		global $limoking_post_settings;
	}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="limoking-blog-content">
		<?php 
			if( is_single() || $limoking_post_settings['excerpt'] < 0 ){
				echo limoking_content_filter(get_the_content(esc_html__( 'Read More', 'limoking' )), true); 
			}else{
				echo limoking_content_filter(get_the_content(esc_html__( 'Read More', 'limoking' ))); 
			}
		?>
	</div>
</article>