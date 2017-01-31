<?php
	/*	
	*	Goodlayers Theme File
	*	---------------------------------------------------------------------
	*	This file contains the function use to print the page buidler section
	*	---------------------------------------------------------------------
	*/

	// printing the pagebuilder item
	if( !function_exists('limoking_get_page_builder') ){
		function limoking_print_page_builder($content, $full_width = true){
		
			$section = array(0, false); // (size, independent)
			foreach( $content as $item ){
			
				// determine the current item size
				$current_size = 1;
				if( !empty($item['size']) ){
					$current_size = limoking_item_size_to_num($item['size']);
				}
				
				// print each section
				if( $item['type'] == 'color-wrapper' || $item['type'] == 'parallax-bg-wrapper' ||
					$item['type'] == 'full-size-wrapper' ){
					$section = limoking_print_section($section, $current_size, true);	
				}else{
					$section = limoking_print_section($section, $current_size, false);	
				}
				
				// start printing item
				if( $item['item-type'] == 'wrapper' ){
					if( $item['type'] == 'color-wrapper' ){
						limoking_print_color_wrapper( $item );
					}else if(  $item['type'] == 'parallax-bg-wrapper'){
						limoking_print_parallax_wrapper( $item );
					}else if(  $item['type'] == 'full-size-wrapper'){
						limoking_print_full_size_wrapper( $item );
					}else{
						limoking_print_column_wrapper( $item );
					}
				}else{
					limoking_print_item( $item );
				}
			}
			
			echo '<div class="clear"></div>';
			
			if( !$section[1] ){ echo '</div>'; } // close container of dependent section
			echo '</section>'; // close the last opened section
			
		}
	}
	
	// print each section
	if( !function_exists('limoking_print_section') ){
		function limoking_print_section( $section, $size, $independent = false ){
			global $limoking_section_id;
			if( empty($limoking_section_id) ){ $limoking_section_id = 1; }

			if( $section[0] == 0 ){ // starting section
				echo '<section id="content-section-' . $limoking_section_id . '" >';
				if( !$independent ){ echo '<div class="section-container container">'; } // open container
				
				$section = array($size, $independent);
				$limoking_section_id ++;
			}else{

				if( $independent || $section[1] ){ // current or previous section is independent
				
					echo '<div class="clear"></div>';
					if( !$section[1] ){ echo '</div>'; } // close container of dependent section
					echo '</section>';
					
					echo '<section id="content-section-' . $limoking_section_id . '" >';		
					if( !$independent ){ echo '<div class="section-container container">'; } // open container
					
					$section[0] = ceil($section[0]) + $size; $section[1] = $independent;
					$limoking_section_id ++;
				}else{

					if( abs((float)$section[0] - floor($section[0])) < 0.01 || 	// is integer or
						(floor($section[0]) < floor($section[0] + $size - 0.01)) ){ 	// exceeding current line
						echo '<div class="clear"></div>';
					}
					if( $size == 1 ){
						echo '<div class="clear"></div>';
						$section[0] = ceil($section[0]) + $size; $section[1] = $independent;
					}else{
						$section[0] += $size; $section[1] = $independent;
					}
				}
			}
			
			return $section;
		}
	}	

	// print the item
	if( !function_exists('limoking_print_item') ){
		function limoking_print_item( $content ){
			switch ($content['type']){
				case 'about-us': echo limoking_get_about_us_item($content['option']); break;
				case 'accordion': echo limoking_get_accordion_item($content['option']); break;
				case 'banner': echo limoking_get_banner_item($content['option']); break;
				case 'toggle-box': echo limoking_get_toggle_box_item($content['option']); break;
				case 'blog': echo limoking_get_blog_item($content['option']); break;
				case 'box-icon-item': echo limoking_get_box_icon_item($content['option']); break;
				case 'feature-media': echo limoking_get_feature_media_item($content['option']); break;
				case 'column-service': echo limoking_get_column_service_item($content['option']); break;
				case 'service-half-background': echo limoking_get_service_half_background($content['option']); break;
				case 'service-with-image': echo limoking_get_service_with_image_item($content['option']); break;
				case 'content': echo limoking_get_content_item($content['option']); break;
				case 'divider': echo limoking_get_divider_item($content['option']); break;
				case 'gallery': echo limoking_get_gallery_item($content['option']); break;
				case 'icon-with-list': echo limoking_get_list_with_icon_item($content['option']); break;
				case 'image-frame': echo limoking_get_image_frame_item($content['option']); break;
				case 'menu': echo limoking_get_menu_item($content['option']); break;
				case 'notification': echo limoking_get_notification_item($content['option']); break;
				case 'price-item': echo limoking_get_price_item($content['option']); break;
				case 'price-table': echo limoking_get_price_table_item($content['option']); break;
				case 'personnel': echo limoking_get_personnel_item($content['option']); break;
				case 'pie-chart': echo limoking_get_pie_chart_item($content['option']); break;
				case 'slider': echo limoking_get_slider_item($content['option']); break;
				case 'page': echo limoking_get_page_list_item($content['option']); break;
				case 'post-slider': echo limoking_get_post_slider_item($content['option']); break;
				case 'skill-bar': echo limoking_get_skill_bar_item($content['option']); break;
				case 'skill-item': echo limoking_get_skill_item($content['option']); break;
				case 'stunning-text': echo limoking_get_stunning_text_item($content['option']); break;
				case 'styled-box': echo limoking_get_styled_box_item($content['option']); break;
				case 'tab': echo limoking_get_tab_item($content['option']); break;
				case 'title': echo limoking_get_title_item($content['option']); break;
				case 'testimonial': echo limoking_get_testimonial_item($content['option']); break;
				case 'video': echo limoking_get_video_item($content['option']); break;
				default: 
				do_action('limoking_print_item_selector', $content['type'], $content['option']); break;
			}
		}	
	}
	
	//////////////////////// Item Wrapper Section ///////////////////////////
	
	// print color wrapper
	if( !function_exists('limoking_print_color_wrapper') ){
		function limoking_print_color_wrapper( $content ){
			$item_id = empty($content['option']['page-item-id'])? '': ' id="' . $content['option']['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$padding  = (!empty($content['option']['padding-top']) && 
				($limoking_spaces['top-wrapper'] != $content['option']['padding-top']))? 
				'padding-top: ' . $content['option']['padding-top'] . '; ': '';
			$padding .= (!empty($content['option']['padding-bottom']) && 
				($limoking_spaces['bottom-wrapper'] != $content['option']['padding-bottom']))? 
				'padding-bottom: ' . $content['option']['padding-bottom'] . '; ': '';
				
			$border = '';
			if( !empty($content['option']['border']) && $content['option']['border'] != 'none' ){
				if($content['option']['border'] == 'top' || $content['option']['border'] == 'both'){
					$border .= ' border-top: 2px solid '. esc_attr($content['option']['border-top-color']) . '; ';
				}
				if($content['option']['border'] == 'bottom' || $content['option']['border'] == 'both'){
					$border .= ' border-bottom: 2px solid '. esc_attr($content['option']['border-bottom-color']) . '; ';
				}
			}

			$content['option']['show-section'] = !empty($content['option']['show-section'])? $content['option']['show-section']: '';
			echo '<div class="limoking-color-wrapper ' . ' ' . esc_attr($content['option']['show-section']) . ' ' . esc_attr($content['option']['skin']) . '" ' . $item_id;
			if( !empty($content['option']['background']) || !empty($padding) ){
				echo 'style="';
				if( empty($content['option']['background-type']) || $content['option']['background-type'] == 'color' ){
					echo !empty($content['option']['background'])? 'background-color: ' . esc_attr($content['option']['background']) . '; ': '';
				}
				echo limoking_escape_content($border);
				echo limoking_escape_content($padding);
				echo '" ';
			}
			echo '>';
			echo '<div class="container">';
			$size = 0; 
			foreach( $content['items'] as $item ){
				if( $item['item-type'] == 'wrapper' ){
					if( $size != 0 && abs($size - intval($size)) < 0.01 ){
						echo '<div class="clear"></div>';
					} 
					limoking_print_column_wrapper( $item );
					$size += limoking_item_size_to_num($item['size']);
				}else{
					if( $size > 0 ){
						$size = 0;
						echo '<div class="clear"></div>';
					}
					limoking_print_item( $item );
					echo '<div class="clear"></div>';
				}
			}
			echo '<div class="clear"></div>';
			echo '</div>'; // close container
			echo '</div>'; // close wrapper
		}
	}	
	
	// print parallax wrapper
	if( !function_exists('limoking_print_parallax_wrapper') ){
		function limoking_print_parallax_wrapper( $content ){
			global $parallax_wrapper_id;
			$parallax_wrapper_id = empty($parallax_wrapper_id)? 1: $parallax_wrapper_id;
			if( empty($content['option']['page-item-id']) ){
				$content['option']['page-item-id'] = 'limoking-parallax-wrapper-' . $parallax_wrapper_id;
				$parallax_wrapper_id++;
			}
			$item_id = ' id="' . $content['option']['page-item-id'] . '" ';

			global $limoking_spaces;
			$padding  = (!empty($content['option']['padding-top']) && 
				($limoking_spaces['top-wrapper'] != $content['option']['padding-top']))? 
				'padding-top: ' . $content['option']['padding-top'] . '; ': '';
			$padding .= (!empty($content['option']['padding-bottom']) && 
				($limoking_spaces['bottom-wrapper'] != $content['option']['padding-bottom']))? 
				'padding-bottom: ' . $content['option']['padding-bottom'] . '; ': '';

			$border = '';
			if( !empty($content['option']['border']) && $content['option']['border'] != 'none' ){
				if($content['option']['border'] == 'top' || $content['option']['border'] == 'both'){
					$border .= ' border-top: 2px solid '. $content['option']['border-top-color'] . '; ';
				}
				if($content['option']['border'] == 'bottom' || $content['option']['border'] == 'both'){
					$border .= ' border-bottom: 2px solid '. $content['option']['border-bottom-color'] . '; ';
				}
			}
			
			$content['option']['show-section'] = !empty($content['option']['show-section'])? $content['option']['show-section']: '';			
			echo '<div class="limoking-parallax-wrapper limoking-background-' . $content['option']['type'] . ' ';
			echo esc_attr($content['option']['show-section']) . ' ';
			echo esc_attr($content['option']['skin']) . '" ' . $item_id;
			
			// background parallax
			if( !empty($content['option']['background']) && $content['option']['type'] == 'image' ){
				if( !empty($content['option']['background-speed']) ){
					echo 'data-bgspeed="' . esc_attr($content['option']['background-speed']) . '" ';
				}else{
					echo 'data-bgspeed="0" ';
				}		
				$repeat = '';
				if( !empty($content['option']['background-repeat']) ){
					$repeat = "background-repeat: {$content['option']['background-repeat']};";
				}
				if( is_numeric($content['option']['background']) ){
					$background = wp_get_attachment_image_src($content['option']['background'], 'full');
					$background = $background[0];
				}else{
					$background = $content['option']['background'];
				}
				echo 'style="background-image: url(\'' . $background . '\'); ' . $repeat . $padding . $border . '" >';			
				
				if( !empty($content['option']['background-mobile']) ){
					if( is_numeric($content['option']['background-mobile']) ){
						$background = wp_get_attachment_image_src($content['option']['background-mobile'], 'full');
						$background = $background[0];
					}else{
						$background = $content['option']['background-mobile'];
					}				
				
					echo '<style type="text/css">@media only screen and (max-width: 767px){ ';
					echo '#' . $content['option']['page-item-id'] . '{';
					echo ' background-image: url(\'' . $background . '\') !important;';
					echo '}';
					echo '}</style>';
				}
				
			// background pattern 
			}else if($content['option']['type'] == 'pattern'){
				$background = get_template_directory_uri() . '/images/pattern/pattern-' . $content['option']['pattern'] . '.png';
				echo 'style="background-image: url(\'' . $background . '\'); ' . $padding . $border . '" >';
			
			// background video
			}else if( $content['option']['type'] == 'video' ){
				echo 'style="' . $padding . $border . '" >';
				
				global $limoking_gallery_id; $limoking_gallery_id++;
				$overlay_opacity = (empty($content['option']['video-overlay']))? 0: floatval($content['option']['video-overlay']);
				
				echo '<div id="limoking-player-' . $limoking_gallery_id . '" class="limoking-bg-player" data-property="';
				echo '{videoURL:\'' . $content['option']['video'] . '\',containment:\'#limoking-player-' . $limoking_gallery_id . '\',';
				echo 'startAt:0,mute:true,autoPlay:true,loop:true,printUrl:false,realfullscreen:false,quality:\'hd720\'';
				echo (!empty($content['option']['video-player']) && $content['option']['video-player'] == 'disable')? ',showControls:false':'';
				echo '}"><div class="limoking-player-overlay" ';
				echo 'style="opacity: ' . $overlay_opacity . '; filter: alpha(opacity=' . $overlay_opacity * 100 . ');" ';
				echo '></div></div>';

			// background video / none
			}else if(!empty($padding) || !empty($border) ){
				echo 'style="' . $padding . $border . '" >';
			}else{
				echo ' >';
			}

			echo '<div class="container">';
			$size = 0; 
			foreach( $content['items'] as $item ){
				if( $item['item-type'] == 'wrapper' ){
					if( $size != 0 && abs($size - intval($size)) < 0.01 ){
						echo '<div class="clear"></div>';
					} 
					limoking_print_column_wrapper( $item );
					$size += limoking_item_size_to_num($item['size']);
				}else{
					if( $size > 0 ){
						$size = 0;
						echo '<div class="clear"></div>';
					}
					limoking_print_item( $item );
					echo '<div class="clear"></div>';
				}
			}
			echo '<div class="clear"></div>';
			echo '</div>'; // close container
			echo '</div>'; // close wrapper
		}
	}
	
	// print full size wrapper
	if( !function_exists('limoking_print_full_size_wrapper') ){
		function limoking_print_full_size_wrapper( $content ){
			$item_id = empty($content['option']['page-item-id'])? '': ' id="' . $content['option']['page-item-id'] . '" ';

			global $limoking_spaces;
			$padding  = (!empty($content['option']['padding-top']) && 
				($limoking_spaces['top-full-wrapper'] != $content['option']['padding-top']))? 
				'padding-top: ' . $content['option']['padding-top'] . '; ': '';
			$padding .= (!empty($content['option']['padding-bottom']) && 
				($limoking_spaces['bottom-wrapper'] != $content['option']['padding-bottom']))? 
				'padding-bottom: ' . $content['option']['padding-bottom'] . '; ': '';

			$border = '';
			if( !empty($content['option']['border']) && $content['option']['border'] != 'none' ){
				if($content['option']['border'] == 'top' || $content['option']['border'] == 'both'){
					$border .= ' border-top: 2px solid '. $content['option']['border-top-color'] . '; ';
				}
				if($content['option']['border'] == 'bottom' || $content['option']['border'] == 'both'){
					$border .= ' border-bottom: 2px solid '. $content['option']['border-bottom-color'] . '; ';
				}
			}			
			
			$background = !empty($content['option']['background'])? ' background-color: ' . $content['option']['background'] . '; ': '';			
			$content['option']['show-section'] = !empty($content['option']['show-section'])? $content['option']['show-section']: '';
			
			$style = (!empty($padding) || !empty($border) || !empty($background))? ' style="' . $padding . $border . $background . '" ': '';
			echo '<div class="limoking-full-size-wrapper ' . $content['option']['show-section'] . ' ' . esc_attr($content['option']['skin']) . '" ' . $item_id . $style . ' >';
			$size = 0; 
			foreach( $content['items'] as $item ){
				if( $item['item-type'] == 'wrapper' ){
					if( $size != 0 && abs($size - intval($size)) < 0.01 ){
						echo '<div class="clear"></div>';
					} 
					limoking_print_column_wrapper( $item );
					$size += limoking_item_size_to_num($item['size']);
				}else{
					if( $size > 0 ){
						$size = 0;
						echo '<div class="clear"></div>';
					}
					limoking_print_item( $item );
					echo '<div class="clear"></div>';
				}
			}	
			echo '<div class="clear"></div>';
			echo '</div>'; // close wrapper
		}
	}	
	
	// get column class
	if( !function_exists('limoking_get_column_class') ){
		function limoking_get_column_class( $size ){
			switch( $size ){
				case '1/9': return 'one-ninth column'; break;
				case '1/8': return 'one-eighth column'; break;
				case '1/7': return 'one-seventh column'; break;			
				case '1/6': return 'two columns'; break;
				case '1/5': return 'one-fifth column'; break;
				case '1/4': return 'three columns'; break;
				case '2/5': return 'two-fifth columns'; break;
				case '1/3': return 'four columns'; break;
				case '1/2': return 'six columns'; break;
				case '3/5': return 'three-fifth columns'; break;
				case '2/3': return 'eight columns'; break;
				case '3/4': return 'nine columns'; break;
				case '4/5': return 'four-fifth columns'; break;
				case '1/1': return 'twelve columns'; break;
				default : return 'twelve columns'; break;
			}
		}
	}
	
	// print column wrapper
	if( !function_exists('limoking_print_column_wrapper') ){
		function limoking_print_column_wrapper( $content ){
			
			echo '<div class="' . limoking_get_column_class( $content['size'] ) . '" >';
			foreach( $content['items'] as $item ){
				limoking_print_item( $item );
			}			
			echo '</div>'; // end of column section
		}
	}	
	
?>