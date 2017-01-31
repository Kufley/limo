<?php get_header(); ?>
<div class="limoking-content">

	<?php 
		global $limoking_sidebar, $theme_option;
		$limoking_sidebar = array(
			'type'=>'no-sidebar',
			'left-sidebar'=>'', 
			'right-sidebar'=>''
		); 
		$limoking_sidebar = limoking_get_sidebar_class($limoking_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container">
			<div class="with-sidebar-left <?php echo esc_attr($limoking_sidebar['outer']); ?> columns">
				<div class="with-sidebar-content <?php echo esc_attr($limoking_sidebar['center']); ?> limoking-item-start-content columns">
					<?php		
						// set the excerpt length
						if( !empty($theme_option['archive-num-excerpt']) ){
							global $limoking_excerpt_length; $limoking_excerpt_length = 55;
							add_filter('excerpt_length', 'limoking_set_excerpt_length');
						} 

						global $wp_query, $limoking_post_settings;
						$limoking_lightbox_id++;
						$limoking_post_settings['excerpt'] = 55;
						$limoking_post_settings['thumbnail-size'] = 'full';			
						$limoking_post_settings['blog-style'] = 'blog-full';							
					
						echo '<div class="blog-item-holder">';
						if($limoking_post_settings['blog-style'] == 'blog-full'){
							$limoking_post_settings['blog-info'] = array('author', 'date', 'category', 'comment');
							echo limoking_get_blog_full($wp_query);
						}else{
							$limoking_post_settings['blog-info'] = array('date', 'comment');
							$limoking_post_settings['blog-info-widget'] = true;
							
							$blog_size = str_replace('blog-1-', '', $theme_option['archive-blog-style']);
							echo limoking_get_blog_grid($wp_query, $blog_size, 
								$theme_option['archive-thumbnail-size'], 'fitRows');
						}
						echo '<div class="clear"></div>';
						echo '</div>';
						remove_filter('excerpt_length', 'limoking_set_excerpt_length');
						
						$paged = (get_query_var('paged'))? get_query_var('paged') : 1;
						echo limoking_get_pagination($wp_query->max_num_pages, $paged);													
					?>
				</div>
				<?php get_sidebar('left'); ?>
				<div class="clear"></div>
			</div>
			<?php get_sidebar('right'); ?>
			<div class="clear"></div>
		</div>				
	</div>				

</div><!-- limoking-content -->
<?php get_footer(); ?>