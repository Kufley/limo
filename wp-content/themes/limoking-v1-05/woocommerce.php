<?php get_header(); ?>
	<div class="limoking-content">

		<?php 
				global $limoking_sidebar, $theme_option;
				$woo_page = (is_product())? 'single': 'all';
				
				$limoking_sidebar = array(
					'type'=>$theme_option[$woo_page . '-products-sidebar'],
					'left-sidebar'=>$theme_option[$woo_page . '-products-sidebar-left'], 
					'right-sidebar'=>$theme_option[$woo_page . '-products-sidebar-right']
				); 
				$limoking_sidebar = limoking_get_sidebar_class($limoking_sidebar);
		?>
		<div class="with-sidebar-wrapper">
			<div class="with-sidebar-container container">
				<div class="with-sidebar-left <?php echo esc_attr($limoking_sidebar['outer']); ?> columns">
					<div class="with-sidebar-content <?php echo esc_attr($limoking_sidebar['center']); ?> columns limoking-item-start-content">
						<div class="limoking-item woocommerce-content-item">
							<div class="woocommerce-breadcrumbs">
							<?php woocommerce_breadcrumb(); ?>
							</div>
				
							<div class="woocommerce-content">
							<?php woocommerce_content(); ?>
							</div>				
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