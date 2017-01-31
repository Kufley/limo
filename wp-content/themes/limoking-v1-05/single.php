<?php get_header(); ?>
<div class="limoking-content">

	<?php 
		global $limoking_sidebar, $theme_option;
		if( empty($limoking_post_option['sidebar']) || $limoking_post_option['sidebar'] == 'default-sidebar' ){
			$limoking_sidebar = array(
				'type'=>$theme_option['post-sidebar-template'],
				'left-sidebar'=>$theme_option['post-sidebar-left'], 
				'right-sidebar'=>$theme_option['post-sidebar-right']
			); 
		}else{
			$limoking_sidebar = array(
				'type'=>$limoking_post_option['sidebar'],
				'left-sidebar'=>$limoking_post_option['left-sidebar'], 
				'right-sidebar'=>$limoking_post_option['right-sidebar']
			); 				
		}
		$limoking_sidebar = limoking_get_sidebar_class($limoking_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container">
			<div class="with-sidebar-left <?php echo esc_attr($limoking_sidebar['outer']); ?> columns">
				<div class="with-sidebar-content <?php echo esc_attr($limoking_sidebar['center']); ?> columns">
					<div class="limoking-item limoking-blog-full limoking-item-start-content">
					<?php while ( have_posts() ){ the_post(); ?>
					
						<!-- get the content based on post format -->
						<?php get_template_part('single/content'); ?>
						
						<?php limoking_get_social_shares(); ?>
						
						<nav class="limoking-single-nav">
							<?php previous_post_link('<div class="previous-nav">%link</div>', '<i class="icon-angle-left"></i><span>%title</span>', true); ?>
							<?php next_post_link('<div class="next-nav">%link</div>', '<span>%title</span><i class="icon-angle-right"></i>', true); ?>
							<div class="clear"></div>
						</nav><!-- .nav-single -->

						<!-- abou author section -->
						<?php if($theme_option['single-post-author'] != "disable"){ ?>
							<div class="limoking-post-author">
							<h3 class="post-author-title" ><?php echo esc_html__('About Post Author', 'limoking'); ?></h3>
							<div class="post-author-avartar"><?php echo get_avatar(get_the_author_meta('ID'), 90); ?></div>
							<div class="post-author-content">
							<h4 class="post-author"><?php the_author_posts_link(); ?></h4>
							<?php echo get_the_author_meta('description'); ?>
							</div>
							<div class="clear"></div>
							</div>
						<?php } ?>						

						<?php comments_template( '', true ); ?>		
						
					<?php } ?>
					</div>
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