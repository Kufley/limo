<?php
	/*	
	*	Goodlayers WPML Support File
	*/
	
	// add_action('limoking_top_left_menu', 'limoking_get_wpml_nav', 1);
	if(!function_exists('limoking_get_wpml_nav')){
		function limoking_get_wpml_nav(){
			if( function_exists('icl_get_languages') ){
?>
<li class="limoking-mega-menu">
	<a href="#"><i class="fa <?php echo limoking_fa_class('icon-globe'); ?>"></i><?php echo ICL_LANGUAGE_NAME; ?></a>
	<div class="sf-mega">
		<div class="sf-mega-section limoking-wpml-language-selector">
		<?php 
			
				$languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str'); 
				foreach($languages as $language){
					echo '<div class="sub-menu-item">';
					echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="" />';
					echo '<a href="' . $language['url'] . '" >' . $language['translated_name'] . '</a>';
					echo '</div>';
					
				}
		?>
		</div>
	</div>
</li>
<?php		
			}
		}
	}
	
?>