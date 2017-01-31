<?php
/**
 * The default template for displaying standard post format
 */

	if( !is_single() ){ 
		global $limoking_post_settings; 
		if($limoking_post_settings['excerpt'] < 0) global $more; $more = 0;
	}else{
		global $limoking_post_settings, $theme_option;
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="limoking-standard-style">
		<?php get_template_part('single/thumbnail', get_post_format()); ?>	
		
		<div class="blog-content-wrapper" >
			<header class="post-header">
				<?php if( is_single() ){ ?>
					<h1 class="limoking-blog-title"><?php the_title(); ?></h1>
				<?php }else{ ?>
					<h3 class="limoking-blog-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php } ?>	
				
				<?php echo limoking_get_blog_info(array('date', 'author', 'comment', 'category'), true); ?>			
				<div class="clear"></div>
			</header><!-- entry-header -->

			<?php 
				if( is_single() || $limoking_post_settings['excerpt'] < 0 ){
					echo '<div class="limoking-blog-content">';
					echo limoking_content_filter($limoking_post_settings['content'], true);
					wp_link_pages( array( 
						'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'limoking' ) . '</span>', 
						'after' => '</div>', 
						'link_before' => '<span>', 
						'link_after' => '</span>' )
					);
					echo '</div>';
				}else if( $limoking_post_settings['excerpt'] != 0 ){
					echo '<div class="limoking-blog-content">' . get_the_excerpt() . '</div>';
				}
			?>
			
			<?php if( is_single() ){ ?>
			<div class="limoking-single-blog-tag">
				<?php echo limoking_get_blog_info(array('tag'), false); ?>
			</div>
			<?php } ?>
		</div> <!-- blog content wrapper -->
	</div>
</article><!-- #post -->