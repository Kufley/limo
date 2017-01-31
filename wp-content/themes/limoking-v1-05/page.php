<?php get_header(); ?>

	<div class="limoking-content">

		<!-- Above Sidebar Section-->
		<?php global $limoking_post_option, $above_sidebar_content, $with_sidebar_content, $below_sidebar_content; ?>
		<?php if(!empty($above_sidebar_content)){ ?>
			<div class="above-sidebar-wrapper"><?php limoking_print_page_builder($above_sidebar_content); ?></div>
		<?php } ?>
		
		<!-- Sidebar With Content Section-->
		<?php 
			if( !empty($limoking_post_option['sidebar']) && ($limoking_post_option['sidebar'] != 'no-sidebar' )){
				global $limoking_sidebar;
				
				$limoking_sidebar = array(
					'type'=>$limoking_post_option['sidebar'],
					'left-sidebar'=>$limoking_post_option['left-sidebar'], 
					'right-sidebar'=>$limoking_post_option['right-sidebar']
				); 
				$limoking_sidebar = limoking_get_sidebar_class($limoking_sidebar);
		?>
			<div class="with-sidebar-wrapper">
				<div class="with-sidebar-container container">
					<div class="with-sidebar-left <?php echo esc_attr($limoking_sidebar['outer']); ?> columns">
						<div class="with-sidebar-content <?php echo esc_attr($limoking_sidebar['center']); ?> columns">
							<?php 
								if( !empty($with_sidebar_content) ){
									limoking_print_page_builder($with_sidebar_content, false);
								}
								if( !empty($limoking_post_option['show-content']) && $limoking_post_option['show-content'] != 'disable' ){
									get_template_part('single/content', 'page');
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
		<?php 
			}else{ 
				if( !empty($with_sidebar_content) ){ 
					echo '<div class="with-sidebar-wrapper">';
					limoking_print_page_builder($with_sidebar_content);
					echo '</div>';
				}
				if( empty($limoking_post_option['show-content']) || $limoking_post_option['show-content'] != 'disable' ){
					get_template_part('single/content', 'page');
				}
			} 
		?>

		<!-- Below Sidebar Section-->
		<?php if(!empty($below_sidebar_content)){ ?>
			<div class="below-sidebar-wrapper"><?php limoking_print_page_builder($below_sidebar_content); ?></div>
		<?php } ?>

		<?php 
			if( comments_open() ){
				echo '<div class="comments-container container" ><div class="comment-item limoking-item">';
				comments_template( '', true ); 
				echo '</div></div>';
			}
		?>
		
	</div><!-- limoking-content -->
<?php get_footer(); ?>