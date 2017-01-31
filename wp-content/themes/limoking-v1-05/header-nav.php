<?php 
	global $theme_option;

	echo '<div class="limoking-navigation-wrapper">';

	// navigation
	if( has_nav_menu('main_menu') ){
		if( class_exists('limoking_menu_walker') ){
			echo '<nav class="limoking-navigation" id="limoking-main-navigation" >';
			wp_nav_menu( array(
				'theme_location'=>'main_menu', 
				'container'=> '', 
				'menu_class'=> 'sf-menu limoking-main-menu',
				'walker'=> new limoking_menu_walker() 
			) );
		}else{
			echo '<nav class="limoking-navigation" role="navigation">';
			wp_nav_menu( array('theme_location'=>'main_menu') );
		}
		
		$icon_style = empty($theme_option['bucket-color'])? 'dark': $theme_option['bucket-color'];
?>
<img id="limoking-menu-search-button" src="<?php echo get_template_directory_uri() . '/images/magnifier-' . $icon_style . '.png'; ?>" alt="" width="58" height="59" />
<div class="limoking-menu-search" id="limoking-menu-search">
	<form method="get" id="searchform" action="<?php  echo esc_url(home_url('/')); ?>/">
		<?php
			$search_val = get_search_query();
			if( empty($search_val) ){
				$search_val = esc_html__("Type Keywords" , "limoking");
			}
		?>
		<div class="search-text">
			<input type="text" value="<?php echo esc_attr($search_val); ?>" name="s" autocomplete="off" data-default="<?php echo esc_attr($search_val); ?>" />
		</div>
		<input type="submit" value="" />
		<div class="clear"></div>
	</form>	
</div>		
<?php		
		if( !empty($theme_option['enable-cart-icon']) && $theme_option['enable-cart-icon'] == 'enable' ){
			limoking_get_woocommerce_nav($icon_style);
		}
		echo '</nav>'; // limoking-navigation
	}
	
	echo '<div class="limoking-navigation-gimmick" id="limoking-navigation-gimmick"></div>';
	echo '<div class="clear"></div>';
	echo '</div>'; // limoking-navigation-wrapper
?>