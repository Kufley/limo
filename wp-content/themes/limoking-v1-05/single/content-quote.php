<?php
/**
 * The template for displaying quote post format
 */
 
	if( !is_single() ){ 
		global $limoking_post_settings;
	}

	$post_format_data = '';
	$content = trim(get_the_content(esc_html__( 'Read More', 'limoking' )));	
	if(preg_match('#^\[gdlr_quote[\s\S]+\[/gdlr_quote\]#', $content, $match)){ 
		$post_format_data = limoking_content_filter($match[0]);
		$content = substr($content, strlen($match[0]));
	}else if(preg_match('#^<blockquote[\s\S]+</blockquote>#', $content, $match)){ 
		$post_format_data = limoking_content_filter($match[0]);
		$content = substr($content, strlen($match[0]));
	}		
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="limoking-blog-content">
		<div class="limoking-top-quote">
			<?php echo limoking_escape_content($post_format_data); ?>
		</div>
		<div class="limoking-quote-author">
		<?php 
			if( is_single() || $limoking_post_settings['excerpt'] < 0 ){
				echo limoking_content_filter($content, true); 
			}else{
				echo limoking_content_filter($content); 
			}
		?>	
		</div>
	</div>
</article><!-- #post -->