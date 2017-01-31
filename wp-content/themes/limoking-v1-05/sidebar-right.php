<?php
/**
 * A template for calling the right sidebar in everypage
 */
 
	global $limoking_sidebar;
?>

<?php if( $limoking_sidebar['type'] == 'right-sidebar' || $limoking_sidebar['type'] == 'both-sidebar' ){ ?>
<div class="limoking-sidebar limoking-right-sidebar <?php echo esc_attr($limoking_sidebar['right']); ?> columns">
	<div class="limoking-item-start-content sidebar-right-item" >
	<?php 
		$sidebar_id = limoking_get_sidebar_id($limoking_sidebar['right-sidebar']);
		if( is_active_sidebar($sidebar_id) ){ 
			dynamic_sidebar($sidebar_id); 
		}
	?>
	</div>
</div>
<?php } ?>