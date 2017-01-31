<?php 
	while ( have_posts() ){ the_post();
		$content = limoking_content_filter(get_the_content(), true); 
		if(!empty($content)){
			?>
			<div class="main-content-container container limoking-item-start-content">
				<div class="limoking-item limoking-main-content">
					<?php echo limoking_escape_content($content); ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
	} 
?>