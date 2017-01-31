<?php
/**
 * A template for calling the left sidebar on everypage
 */
 
	global $limoking_sidebar;
?>

<?php if( $limoking_sidebar['type'] == 'left-sidebar' || $limoking_sidebar['type'] == 'both-sidebar' ){ ?>
<div class="limoking-sidebar limoking-left-sidebar <?php echo esc_attr($limoking_sidebar['left']); ?> columns">
	<div class="limoking-item-start-content sidebar-left-item" >
	<?php 
		$sidebar_id = limoking_get_sidebar_id($limoking_sidebar['left-sidebar']);
		if( is_active_sidebar($sidebar_id) ){ 
			dynamic_sidebar($sidebar_id); 
		}
	?>
	</div>
</div>
<?php } ?>