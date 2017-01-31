<?php 
	if( !is_single() ){ 
		global $limoking_post_settings; 
	}else{
		global $limoking_post_settings, $theme_option, $limoking_post_option;
	}
	$limoking_post_settings['content'] = get_the_content();
	
	if ( has_post_thumbnail() && ! post_password_required() ){ ?>
		<div class="limoking-blog-thumbnail">
			<?php 
				if( is_single() ){
					echo limoking_get_image(get_post_thumbnail_id(), $theme_option['post-thumbnail-size'], true);	
				}else{
					$temp_option = json_decode(get_post_meta(get_the_ID(), 'post-option', true), true);
					echo '<a href="' . get_permalink() . '"> ';
					echo limoking_get_image(get_post_thumbnail_id(), $limoking_post_settings['thumbnail-size']);
					echo '</a>';
					
					if( is_sticky() ){
						echo '<div class="limoking-sticky-banner">';
						echo '<i class="fa ' . limoking_fa_class('icon-bullhorn') . '" ></i>';
						echo esc_html__('Sticky Post', 'limoking');
						echo '</div>';
					}
				}
			?>
		</div>
<?php 
	} 
?>	