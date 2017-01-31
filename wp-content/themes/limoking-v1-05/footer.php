	<?php global $theme_option; ?>
	<div class="clear" ></div>
	</div><!-- content wrapper -->

	<?php 
		// page style
		global $limoking_post_option;
		if( empty($limoking_post_option) || empty($limoking_post_option['page-style']) ||
			  $limoking_post_option['page-style'] == 'normal' || 
			  $limoking_post_option['page-style'] == 'no-header'){ 
	?>	
	<footer class="footer-wrapper" >
		<?php if( $theme_option['show-footer'] != 'disable' ){ ?>
		<div class="footer-container container">
			<?php 	
				$i = 1;
				$theme_option['footer-layout'] = empty($theme_option['footer-layout'])? '1': $theme_option['footer-layout'];
				$limoking_footer_layout = array(
					'1'=>array('twelve columns'),
					'2'=>array('three columns', 'three columns', 'three columns', 'three columns'),
					'3'=>array('three columns', 'three columns', 'six columns',),
					'4'=>array('four columns', 'four columns', 'four columns'),
					'5'=>array('four columns', 'four columns', 'eight columns'),
					'6'=>array('eight columns', 'four columns', 'four columns'),
				);
			?>
			<?php foreach( $limoking_footer_layout[$theme_option['footer-layout']] as $footer_class ){ ?>
				<div class="footer-column <?php echo esc_attr($footer_class); ?>" id="footer-widget-<?php echo esc_attr($i); ?>" >
					<?php 
						$sidebar_id = limoking_get_sidebar_id('Footer ' . $i);
						if( is_active_sidebar($sidebar_id) ){
							dynamic_sidebar($sidebar_id); 
						}
					?>
				</div>
			<?php $i++; ?>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php } ?>
		
		<?php if( $theme_option['show-copyright'] != 'disable' ){ ?>
		<div class="copyright-wrapper">
			<div class="copyright-container container">
				<div class="copyright-left">
					<?php if( !empty($theme_option['copyright-left-text']) ) echo limoking_escape_string(limoking_text_filter($theme_option['copyright-left-text'])); ?>
				</div>
				<div class="copyright-right">
					<?php if( !empty($theme_option['copyright-right-text']) ) echo limoking_escape_string(limoking_text_filter($theme_option['copyright-right-text'])); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php } ?>
	</footer>
	<?php } // page style ?>
</div> <!-- body-wrapper -->
<?php wp_footer(); ?>
</body>
</html>