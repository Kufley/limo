<?php get_header(); ?>
<div class="limoking-content">

	<?php 
		global $limoking_sidebar, $theme_option;
		$limoking_sidebar = array(
			'type'=>$theme_option['archive-sidebar-template'],
			'left-sidebar'=>$theme_option['archive-sidebar-left'], 
			'right-sidebar'=>$theme_option['archive-sidebar-right']
		); 
		$limoking_sidebar = limoking_get_sidebar_class($limoking_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container">
			<div class="with-sidebar-left <?php echo esc_attr($limoking_sidebar['outer']); ?> columns">
				<div class="with-sidebar-content <?php echo esc_attr($limoking_sidebar['center']); ?> limoking-item-start-content columns">
					<?php
						if( have_posts() ){
							// set the excerpt length
							if( !empty($theme_option['archive-num-excerpt']) ){
								global $limoking_excerpt_length; $limoking_excerpt_length = $theme_option['archive-num-excerpt'];
								add_filter('excerpt_length', 'limoking_set_excerpt_length');
							} 

							global $wp_query, $limoking_post_settings;
							$limoking_lightbox_id++;
							$limoking_post_settings['excerpt'] = intval($theme_option['archive-num-excerpt']);
							$limoking_post_settings['thumbnail-size'] = $theme_option['archive-thumbnail-size'];			
							$limoking_post_settings['blog-style'] = $theme_option['archive-blog-style'];							
						
							echo '<div class="blog-item-holder">';
							if($theme_option['archive-blog-style'] == 'blog-full'){
								echo limoking_get_blog_full($wp_query);
							}else if($theme_option['archive-blog-style'] == 'blog-medium'){
								echo limoking_get_blog_medium($wp_query);			
							}else{
								$blog_size = str_replace('blog-1-', '', $theme_option['archive-blog-style']);
								echo limoking_get_blog_grid($wp_query, $blog_size, 'fitRows');
							}
							echo '</div>';
							remove_filter('excerpt_length', 'limoking_set_excerpt_length');
							
							$paged = (get_query_var('paged'))? get_query_var('paged') : 1;
							echo limoking_get_pagination($wp_query->max_num_pages, $paged);
						}else{
?>
<div class="limoking-item page-not-found-item">							
	<div class="page-not-found-block" >
		<div class="page-not-found-icon">
			<i class="fa fa-frown-o"></i>
		</div>
		<div class="page-not-found-title">
			<?php esc_html_e('Not Found', 'limoking'); ?>
		</div>
		<div class="page-not-found-caption">
			<?php esc_html_e('Nothing matched your search criteria. Please try again with different keywords.', 'limoking'); ?>
		</div>
		<div class="page-not-found-search">
			<?php get_search_form(); ?>
		</div>
	</div>							
</div>							
<?php
						}
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