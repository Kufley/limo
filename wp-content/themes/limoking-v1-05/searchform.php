<div class="gdl-search-form">
	<form method="get" id="searchform" action="<?php  echo esc_url(home_url('/')); ?>/">
		<?php
			$search_val = get_search_query();
			if( empty($search_val) ){
				$search_val = esc_html__("Type keywords..." , "limoking");
			}
		?>
		<div class="search-text" id="search-text">
			<input type="text" name="s" id="s" autocomplete="off" data-default="<?php echo esc_attr($search_val); ?>" />
		</div>
		<input type="submit" id="searchsubmit" value="" />
		<div class="clear"></div>
	</form>
</div>