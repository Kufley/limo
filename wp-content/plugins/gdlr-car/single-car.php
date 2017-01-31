<?php 
	get_header(); 
	
	global $limoking_contact_car, $wp_query;
	$limoking_contact_car = clone $wp_query;
	
	while( have_posts() ){ the_post();
?>
<div class="limoking-content">

	<?php 
		global $limoking_sidebar, $theme_option, $limoking_post_option, $limoking_is_ajax;
		
		
		$limoking_sidebar = array(
			'type'=> 'no-sidebar',
			'left-sidebar'=> '', 
			'right-sidebar'=> ''
		); 				
		$limoking_sidebar = limoking_get_sidebar_class($limoking_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container limoking-class-<?php echo $limoking_sidebar['type']; ?>">
			<div class="with-sidebar-left <?php echo $limoking_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $limoking_sidebar['center']; ?> columns">
					<div class="limoking-item limoking-item-start-content">
						<div id="car-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="limoking-car-info-wrapper" >
								<?php 
								if( !empty($limoking_post_option['car-info-thumbnail']) ){
									echo '<div class="limoking-car-info-thumbnail">' . limoking_get_image($limoking_post_option['car-info-thumbnail']) . '</div>';
								}
								?>
								<h1 class="limoking-car-title"><?php the_title(); ?></h1>
								
								<div class="limoking-car-rate-wrapper" >
									<?php if( !empty($limoking_post_option['per-hour-rate-amount']) ){ ?>
									<div class="limoking-car-rate per-hour-rate" >
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/per-hour-rate.png'; ?>" alt="per-hour-rate" />
										<div class="limoking-car-rate-info-wrapper" >
											<div class="car-rate-info-head limoking-title-font" ><?php _e('Per Hour Rate', 'limoking'); ?></div>
											<div class="car-rate-info" >
												<span class="car-rate-info-amount" >
													<span class="car-rate-info-price" ><?php echo limoking_escape_content($limoking_post_option['per-hour-rate-amount']); ?></span>
													<?php _e('/ Hour', 'limoking'); ?>
												</span>
												<span class="car-rate-info-caption" ><?php echo limoking_escape_content($limoking_post_option['per-hour-rate-caption']); ?></span>
											</div>
										</div>
									</div>
									<?php } ?>
									<?php if( !empty($limoking_post_option['per-day-rate-amount']) ){ ?>
									<div class="limoking-car-rate per-day-rate" >
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/per-day-rate.png'; ?>" alt="per-day-rate" />
										<div class="limoking-car-rate-info-wrapper" >
											<div class="car-rate-info-head limoking-title-font" ><?php _e('Per Day Rate', 'limoking'); ?></div>
											<div class="car-rate-info" >
												<span class="car-rate-info-amount" >
													<span class="car-rate-info-price" ><?php echo limoking_escape_content($limoking_post_option['per-day-rate-amount']); ?></span>
													<?php _e('/ Day', 'limoking'); ?>
												</span>
												<span class="car-rate-info-caption" ><?php echo limoking_escape_content($limoking_post_option['per-day-rate-caption']); ?></span>
											</div>
										</div>
									</div>
									<?php } ?>
									<?php if( !empty($limoking_post_option['airport-transfer-amount']) ){ ?>
									<div class="limoking-car-rate airport-transfer-rate" >
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/airport-transfer.png'; ?>" alt="per-hour-rate" />
										<div class="limoking-car-rate-info-wrapper" >
											<div class="car-rate-info-head limoking-title-font" ><?php _e('Airport Transfer', 'limoking'); ?></div>
											<div class="car-rate-info" >
												<span class="car-rate-info-amount" >
													<span class="car-rate-info-price" ><?php echo limoking_escape_content($limoking_post_option['airport-transfer-amount']); ?></span>
												</span>
												<span class="car-rate-info-caption" ><?php echo limoking_escape_content($limoking_post_option['airport-transfer-caption']); ?></span>
											</div>
										</div>
									</div>		
									<?php } ?>
								</div>
								
								<?php 	
									echo limoking_get_car_info($limoking_post_option); 
									if( $button_id = limoking_get_book_now_id() ){
										echo '<a class="single-book-now" href="#' . $button_id . '" data-fancybox-type="inline" data-rel="fancybox">';
										_e('Book Now', 'limoking');
										echo '</a>';
										echo limoking_get_book_now_button();
									}
								?>
							</div>
							<div class="limoking-car-content-wrapper" >
								<?php 
									$thumbnail = limoking_get_car_thumbnail($limoking_post_option);
									if(!empty($thumbnail)){
										echo '<div class="limoking-car-thumbnail ' . limoking_get_car_thumbnail_class($limoking_post_option) . '">';
										echo $thumbnail;
										echo '</div>';
									}
								?>
								<div class="limoking-car-content">
								<?php 
									the_content(); 
									wp_link_pages( array( 
										'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'gdlr-car' ) . '</span>', 
										'after' => '</div>', 
										'link_before' => '<span>', 
										'link_after' => '</span>' ));
								?>		
								<div class="clear"></div>
								</div>
							</div><!-- #car -->
							<div class="clear"></div>
						</div><!-- #car -->
						<?php //  ?>
						
						<div class="clear"></div>	
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
<?php
	}
	
	get_footer(); 
?>