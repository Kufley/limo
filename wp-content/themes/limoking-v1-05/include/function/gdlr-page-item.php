<?php
	/*	
	*	Goodlayers Theme File
	*	---------------------------------------------------------------------
	*	This file contains the function use to print the elements of the theme
	*	---------------------------------------------------------------------
	*/
	
	// print title
	if( !function_exists('limoking_get_item_title') ){
		function limoking_get_item_title( $atts ){
			$ret = '';
			
			$atts['type'] = (empty($atts['type']))? '': $atts['type'];
			$atts['title-type'] = (empty($atts['title-type']))? 'center': $atts['title-type'];
			$atts['title-size'] = (empty($atts['title-size']))? 'medium': $atts['title-size'];
			$atts['carousel'] = (empty($atts['carousel']))? '': $atts['carousel'];
			
			$item_class  = (!empty($atts['carousel']))? ' limoking-nav-container': '';
			$item_class .= ' limoking-' . $atts['title-type'];
			$item_class .= ' limoking-' . $atts['title-size'];
			$item_class .= (!empty($atts['item-class']))? ' ' . $atts['item-class']: '';
			$is_center_title = (strpos($atts['title-type'], 'center') !== false);

			if( !empty($atts['title-type']) && $atts['title-type'] != 'none' && (!empty($atts['title']) || !empty($atts['caption'])) ){
				$ret .= '<div class="limoking-item-title-wrapper limoking-item ' . $item_class . ' ">';
				$ret .= '<div class="limoking-item-title-container container">';
				
				// echo title
				$ret .= '<div class="limoking-item-title-head-inner">';
				$ret .= ($atts['title-type'] == 'center-divider')? '<div class="limoking-item-title-center-divider limoking-left"></div>': '';
				$ret .= '<h3 class="limoking-item-title limoking-skin-title limoking-skin-border">' . $atts['title'] . '</h3>';
				$ret .= ($atts['title-type'] == 'center-divider')? '<div class="limoking-item-title-center-divider limoking-right"></div>': '';
				$ret .= ($atts['title-type'] == 'left-divider')? '<div class="limoking-item-title-left-divider"></div>': '';
				
				// right text and nav for left style
				if( !$is_center_title && (!empty($atts['carousel']) || (!empty($atts['right-text']) && !empty($atts['right-text-link'])))  ){
					$ret .= '<span class="limoking-nav-title">';
					if( !empty($atts['right-text']) && !empty($atts['right-text-link']) ){
						$ret .= '<a class="limoking-item-title-link limoking-info-font" href="' . esc_url($atts['right-text-link']) . '" >' . $atts['right-text'] . '</a>';
					}
					if( !empty($atts['carousel']) ){
						$ret .= limoking_get_item_title_nav( $atts['carousel'] );
					}
					$ret .= '</span>';
				}
				$ret .= '</div>'; // limoking-item-title-head-inner
				
				// nav for center style
				if( $is_center_title && (!empty($atts['carousel']) || $atts['title-type'] == 'center-icon-divider') ){
					
					$additional_html = '';
					if( $atts['title-type'] == 'center-icon-divider' ){
						$additional_html .= '<div class="limoking-item-title-content-icon-divider-wrapper" >';
						$additional_html .= '<div class="limoking-item-title-center-icon-divider"></div>';
						$additional_html .= '<i class="fa ' . $atts['title-icon-class'] . '" ></i>';
						$additional_html .= '<div class="limoking-item-title-center-icon-divider"></div>';
						$additional_html .= '</div>';
					}
					
					$ret .= '<div class="limoking-nav-title">';
					$ret .= limoking_get_item_title_nav($atts['carousel'], $additional_html);
					$ret .= '</div>';
				}
				
				if( !empty($atts['caption']) ){
					$ret .= '<div class="limoking-item-title-caption limoking-skin-info">' . $atts['caption'] . '</div>';
				}
				// right text for center style
				if( $is_center_title && !empty($atts['right-text']) && !empty($atts['right-text-link']) ){
					$ret .= '<a class="limoking-item-title-link limoking-info-font" href="' . esc_url($atts['right-text-link']) . '" >' . $atts['right-text'] . '</a>';
				}
				$ret .= '</div>'; // container
				$ret .= '</div>'; // limoking-item-title-wrapper
			}
	
			return $ret;
		}
	}		

	if( !function_exists('limoking_get_item_title_nav') ){
		function limoking_get_item_title_nav( $carousel, $additional_html = '' ){
			$ret  = '';
			$ret .= empty($carousel)? '': '<i class="icon-angle-left limoking-flex-prev"></i>';
			if( !empty($additional_html) ){ $ret .= $additional_html; }
			$ret .= empty($carousel)? '': '<i class="icon-angle-right limoking-flex-next"></i>';	
			return $ret;
		}
	}	
	
	// title item
	if( !function_exists('limoking_get_title_item') ){
		function limoking_get_title_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
	
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';		
			
			$ret  = '<div class="limoking-title-item" ' . $item_id . $margin_style . ' >';
			$ret .= limoking_get_item_title($settings);			
			$ret .= '</div>';
			return $ret;
		}
	}
	
	// accordion item
	if( !function_exists('limoking_get_accordion_item') ){
		function limoking_get_accordion_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
	
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$accordion = is_array($settings['accordion'])? $settings['accordion']: json_decode($settings['accordion'], true);

			$ret  = limoking_get_item_title($settings);				
			$ret .= '<div class="limoking-item limoking-accordion-item '  . $settings['style'] . '" ' . $item_id . $margin_style . ' >';
			$current_tab = 0;
			foreach( $accordion as $tab ){  $current_tab++;
				$ret .= '<div class="accordion-tab';
				$ret .= ($current_tab == intval($settings['initial-state']))? ' active pre-active" >': '" >';
				$ret .= '<h4 class="accordion-title" ';
				$ret .= empty($tab['gdl-tab-title-id'])? '': 'id="' . $tab['gdl-tab-title-id'] . '" ';
				$ret .= '><i class="';
				$ret .= ($current_tab == intval($settings['initial-state']))? 'icon-minus': 'icon-plus';
				$ret .= '" ></i><span>' . limoking_text_filter($tab['gdl-tab-title']) . '</span></h4>';
				$ret .= '<div class="accordion-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '</div>';				
			}
			$ret .= '</div>';
			
			return $ret;
		}
	}	

	// toggle box item
	if( !function_exists('limoking_get_toggle_box_item') ){
		function limoking_get_toggle_box_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';

			$accordion = is_array($settings['toggle-box'])? $settings['toggle-box']: json_decode($settings['toggle-box'], true);

			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="limoking-item limoking-accordion-item limoking-multiple-tab '  . $settings['style'] . '" ' . $item_id . $margin_style . ' >';
			foreach( $accordion as $tab ){ 
				$ret .= '<div class="accordion-tab';
				$ret .= ($tab['gdl-tab-active'] == 'yes')? ' active pre-active" >': '" >';
				$ret .= '<h4 class="accordion-title" ';
				$ret .= empty($tab['gdl-tab-title-id'])? '': 'id="' . $tab['gdl-tab-title-id'] . '" ';
				$ret .= '><i class="';
				$ret .= ($tab['gdl-tab-active'] == 'yes')? 'icon-minus': 'icon-plus';
				$ret .= '" ></i><span>' . limoking_text_filter($tab['gdl-tab-title']) . '</span></h4>';
				$ret .= '<div class="accordion-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '</div>';
			}
			$ret .= '</div>';
			
			return $ret;
		}
	}		

	// about us item
	if( !function_exists('limoking_get_about_us_item') ){
		function limoking_get_about_us_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['style'] = empty($settings['style'])? 'plain': $settings['style'];
			$ret  = '<div class="limoking-item limoking-about-us-item limoking-' . $settings['style'] . '" ' . $item_id . $margin_style . '>';
			$ret .= '<div class="about-us-title-wrapper">';
				if( !empty($settings['title']) ){
					$ret .= '<h3 class="about-us-title">' . limoking_text_filter($settings['title']) . '</h3>';
				}
				if( !empty($settings['caption']) && $settings['style'] == 'with-caption' ){
					$ret .= '<div class="about-us-caption limoking-info-font limoking-skin-info">' . limoking_text_filter($settings['caption']) . '</div>';
				}
				if( $settings['style'] == 'with-divider' ){
					$ret .= '<div class="about-us-title-divider"></div>';
				}
			$ret .= '</div>'; // about-us-title-wrapper
			
			$ret .= '<div class="about-us-content-wrapper">';
			$ret .= '<div class="about-us-content limoking-skin-content">';
			$ret .= limoking_content_filter($settings['content']);
			$ret .= '</div>'; // about-us-content 
			if( empty($settings['read-more-type']) || $settings['read-more-type'] == 'url' ){
				if( !empty($settings['read-more-text']) && !empty($settings['read-more-link']) ){
					$ret .= '<a class="about-us-read-more limoking-button large" href="' . $settings['read-more-link'] . '" >' . $settings['read-more-text'] . '</a>';
				}
			}else if( !empty($settings['read-more-type']) && $settings['read-more-type'] == 'car-form' ){
				global $limoking_contact_car; $limoking_contact_car = '';
				
				$button_id = limoking_get_book_now_id();
				$ret .= '<a class="about-us-read-more limoking-button large" href="#' . $button_id . '" data-fancybox-type="inline" data-rel="fancybox">' . $settings['read-more-text'] . '</a>';
				$ret .= limoking_get_book_now_button();
			}
			$ret .= '</div>'; // about-us-content-wrapper		
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // about-us-item
			return $ret;
		}
	}
	
	// tab item
	if( !function_exists('limoking_get_menu_item') ){
		function limoking_get_menu_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$tabs = is_array($settings['content'])? $settings['content']: json_decode($settings['content'], true);			
			$current_tab = 0;
			
			$ret  = '<div class="limoking-list-menu" ' . $item_id . $margin_style . '>';
			$ret .= limoking_get_item_title($settings);	
			foreach( $tabs as $tab ){
				$ret .= '<div class="limoking-menu-item-content limoking-item" >';
				$ret .= '<h4 class="limoking-menu-title" >' . limoking_text_filter($tab['gdl-tab-title']) . '</h4>';				
				if( !empty($tab['caption']) ){
					$ret .= '<div class="limoking-menu-ingredients-caption limoking-skin-info">';
					if( !empty($tab['icon']) ){
						$ret .= '<i class="limoking-skin-title limoking-menu-icon fa ' . $tab['icon'] . '" ></i>';
					}
					$ret .= $tab['caption'] . '</div>';
				}
				if( !empty($tab['price']) ){
					$ret .= '<div class="limoking-menu-price limoking-skin-title">' . $tab['price'] . '</div>';
				}
				$ret .= '<div class="limoking-list-menu-gimmick"></div>';
				$ret .= '</div>';
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // limoking-tab-item 
			
			return $ret;
		}
	}		
	
	// price item
	if( !function_exists('limoking_get_price_item') ){
		function limoking_get_price_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['style'] = empty($settings['style'])? 'type-1': $settings['style'];
			$ret  = '<div class="limoking-item limoking-price-item" ' . $item_id . $margin_style . '>';
			if( !empty($settings['image']) ){
				$ret .= '<div class="price-item-image" >' . limoking_get_image($settings['image']) . '</div>';
			}
			$ret .= '<h3 class="price-item-title">' . limoking_text_filter($settings['title']) . '</h3>';
			$ret .= '<div class="price-item-price limoking-skin-info">' . limoking_text_filter($settings['price']) . '</div>';
			$ret .= '</div>'; // limoking-price-item
			return $ret;
		}
	}	
	
	// column service item
	if( !function_exists('limoking_get_column_service_item') ){
		function limoking_get_column_service_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['style'] = empty($settings['size'])? 'medium': $settings['size'];
			$ret  = '<div class="limoking-ux column-service-ux">';
			
			$ret .= '<div class="limoking-item limoking-column-service-item limoking-' . $settings['style'] . '" ' . $item_id . $margin_style . '>';
			if( $settings['type'] == 'image' && !empty($settings['image']) ){
				$ret .= '<div class="column-service-image" >' . limoking_get_image($settings['image']) . '</div>';
			}else if( $settings['type'] == 'icon' && !empty($settings['icon']) ){
				$ret .= '<div class="column-service-icon limoking-skin-box"><i class="fa ' . $settings['icon'] . '" ></i></div>';
			} 
			$ret .= '<div class="column-service-content-wrapper">';
			if( $settings['type'] == 'no-media' && !empty($settings['caption']) ){
				$ret .= '<div class="column-service-caption limoking-info-font" >' . limoking_text_filter($settings['caption']) . '</div>';
			}
			$ret .= '<h3 class="column-service-title">' . limoking_text_filter($settings['title']) . '</h3>';
			$ret .= '<div class="column-service-content limoking-skin-content">';
			$ret .= limoking_content_filter($settings['content']);
			$ret .= '</div>'; // column-service-content 
			if( !empty($settings['read-more-text']) && !empty($settings['read-more-link']) ){
				$ret .= '<a class="column-service-read-more limoking-info-font" href="' . $settings['read-more-link'] . '" >' . $settings['read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // column-service-content-wrapper			
			$ret .= '</div>'; // column-service-item
			$ret .= '</div>'; // column-service-ux
			return $ret;
		}
	}
	
	// service with image item
	if( !function_exists('limoking_get_service_with_image_item') ){
		function limoking_get_service_with_image_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="limoking-item limoking-service-with-image-item limoking-' . $settings['align'] . '" ';
			$ret .= $item_id . $margin_style . '>';
			if( !empty($settings['image']) ){
				$ret .= '<div class="service-with-image-thumbnail limoking-skin-box">';
				$ret .= limoking_get_image($settings['image'], $settings['thumbnail-size']);
				$ret .= '</div>';
			}
			
			$ret .= '<div class="service-with-image-content-wrapper">';
			$ret .= '<h3 class="service-with-image-title">' . limoking_text_filter($settings['title']) . '</h3>';
			$ret .= '<div class="service-with-image-content">' . limoking_content_filter($settings['content']) . '</div>'; 
			$ret .= '</div>'; // service with image content wrapper
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // limoking-item
			return $ret;
		}
	}	

	// service half background item
	if( !function_exists('limoking_get_service_half_background') ){
		function limoking_get_service_half_background( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$style = '';
			
			$ret  = '<div class="limoking-service-half-background-item" ' . $item_id . $margin_style . '>';

			if( !empty($settings['left-bg-image']) ){
				if( is_numeric($settings['left-bg-image']) ){
					$image_src = wp_get_attachment_image_src($settings['left-bg-image'], 'full');
					$style = 'style="background: url(\'' . $image_src[0] . '\') center 0px;"';
				}else{
					$style = 'style="background: url(\'' . $settings['left-bg-image'] . '\') center 0px;"';
				}
			}else{
				$style = '';
			}
			$ret .= '<div class="limoking-half-left" ' . $style . ' >';
			$ret .= '<div class="half-container">';
			$ret .= '<div class="limoking-item-margin">';
			if( !empty($settings['right-title']) ){
				$ret .= '<h3 class="limoking-left-service-title" >' . limoking_text_filter($settings['left-title']) . '</h3>';
			}
			if( !empty($settings['left-content']) ){
				$ret .= '<div class="limoking-left-service-content" >' . limoking_content_filter($settings['left-content']) . '</div>';
			}
			if( !empty($settings['left-read-more-text']) && $settings['left-read-more-link'] ){
				$ret .= '<a class="limoking-left-service-read-more" href="' . $settings['left-read-more-link'] . '" >' . $settings['left-read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // limoking-item
			$ret .= '</div>'; // half-container
			$ret .= '</div>'; // half-left
			
			if( !empty($settings['right-bg-color']) ){
				$style = 'style="background: ' . $settings['right-bg-color'] . ';"';
			}
			$ret .= '<div class="limoking-half-right" ' . $style . ' >';
			$ret .= '<div class="half-container">';
			$ret .= '<div class="limoking-item-margin">';
			if( !empty($settings['right-title']) ){
				$ret .= '<h3 class="limoking-right-service-title" >' . limoking_text_filter($settings['right-title']) . '</h3>';
			}
			if( !empty($settings['right-content']) ){
				$ret .= '<div class="limoking-right-service-caption" >' . limoking_content_filter($settings['right-content']) . '</div>';
			}
			if( !empty($settings['right-read-more-text']) && $settings['right-read-more-link'] ){
				$ret .= '<a class="limoking-right-service-read-more" href="' . $settings['right-read-more-link'] . '" >' . $settings['right-read-more-text'] . '</a>';
			}
			$ret .= '</div>'; // limoking-item
			$ret .= '</div>'; // half-container
			$ret .= '</div>'; // half-right
			$ret .= '<div class="clear"></div>';

			// $ret .= '<div class="service-with-image-content-wrapper">';
			// $ret .= '<h3 class="service-with-image-title">' . limoking_text_filter($settings['title']) . '</h3>';
			// $ret .= '<div class="service-with-image-content">' . limoking_content_filter($settings['content']) . '</div>'; 
			// $ret .= '</div>'; // service with image content wrapper
			// $ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // limoking-item
			return $ret;
		}
	}	
	
	// feature media item
	if( !function_exists('limoking_get_feature_media_item') ){
		function limoking_get_feature_media_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['align'] = empty($settings['align'])? 'left': $settings['align'];
			$ret  = '<div class="limoking-feature-media-ux limoking-ux">';
			$ret .= '<div class="limoking-item limoking-feature-media-item limoking-' . $settings['align'] . '" ' . $item_id . $margin_style . '>';
			
			if($settings['type'] == 'image' && !empty($settings['image'])){
				$ret .= '<div class="feature-media-thumbnail limoking-image">';
				$ret .= limoking_get_image($settings['image'], $settings['thumbnail-size']);
				$ret .= '</div>';
			}else if($settings['type'] == 'video' && !empty($settings['video-url'])){
				$ret .= '<div class="feature-media-thumbnail limoking-video">';
				$ret .= limoking_get_video($settings['video-url']);
				$ret .= '</div>';
			}
			
			$ret .= '<div class="feature-media-content-wrapper">';
			$ret .= limoking_get_item_title($settings);	
			$ret .= '<div class="feature-media-content">';
			$ret .= limoking_content_filter($settings['content']);
			$ret .= '</div>'; 
			if( !empty($settings['button-link']) ){
				$ret .= '<a class="feature-media-button limoking-button with-border" href="' . esc_url($settings['button-link']) . '" target="_blank">';
				$ret .= $settings['button-text'];
				$ret .= '</a>';
			}			
			$ret .= '</div>'; // feature-media-content-wrapper
			$ret .= '</div>'; // limoking-item
			$ret .= '</div>'; // limoking-ux
			return $ret;
		}
	}		
	
	// content item
	if( !function_exists('limoking_get_content_item') ){
		function limoking_get_content_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="limoking-item limoking-content-item" ' . $item_id . $margin_style . '>' . limoking_content_filter($settings['content']) . '</div>';
			return $ret;
		}
	}	

	// notification item
	if( !function_exists('limoking_get_notification_item') ){
		function limoking_get_notification_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';	
		
			$style  = ' style="';
			if($settings['type'] == 'color-background'){
				$style .= !empty($settings['color'])? 'color:' . $settings['color'] . '; ': '';
				$style .= !empty($settings['background'])? 'background-color:' . $settings['background'] . '; ': '';
			}else if($settings['type'] == 'color-border'){
				$style .= !empty($settings['color'])? 'color:' . $settings['color'] . '; ': '';
				$style .= !empty($settings['border'])? 'border-color:' . $settings['border'] . '; ': '';	
			}	
			$style .= $margin . '" ';
			
			$ret  = '<div class="limoking-notification limoking-item ' . $settings['type'] . '" ' . $style . '>';
			$ret .= '<i class="fa ' . $settings['icon'] . '"></i>';
			$ret .= '<div class="notification-content">' . limoking_text_filter($settings['content']) . '</div>';
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			return $ret;	
		}
	}
	
	// icon with list item
	if( !function_exists('limoking_get_list_with_icon_item') ){
		function limoking_get_list_with_icon_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['icon-with-list'] = empty($settings['icon-with-list'])? array(): $settings['icon-with-list'];
			$list = is_array($settings['icon-with-list'])? $settings['icon-with-list']: json_decode($settings['icon-with-list'], true);

			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="limoking-item limoking-icon-with-list-item" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				$ret .= '<div class="list-with-icon-ux limoking-ux">';
				$ret .= '<div class="list-with-icon limoking-' . $settings['align'] . '">';
				if( !empty($tab['gdl-tab-image']) ){
					$ret .= '<div class="list-with-icon-image">';
					$ret .= limoking_get_image($tab['gdl-tab-image']);
					$ret .= '</div>';
				}else if( !empty($tab['gdl-tab-icon']) ){
					$ret .= '<div class="list-with-icon-icon">';
					$ret .= '<i class="fa ' . $tab['gdl-tab-icon'] . '"></i>';
					$ret .= '</div>';
				}
				$ret .= '<div class="list-with-icon-content">';
				$ret .= '<div class="list-with-icon-title limoking-skin-title">';
				$ret .= limoking_text_filter($tab['gdl-tab-title']);
				$ret .= '</div>';
				$ret .= '<div class="list-with-icon-caption">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				$ret .= '</div>'; // list-with-icon-content
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>'; // icon-with-list
				$ret .= '</div>'; // limoking-ux
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			return $ret;
		}
	}	

	// skill bar item
	if( !function_exists('limoking_get_skill_bar_item') ){
		function limoking_get_skill_bar_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';	
		
		
			$ret  = '<div class="limoking-skill-bar-wrapper  limoking-item limoking-size-' . $settings['size'] . '" ' . $item_id . $margin_style . '>';
			if( $settings['size'] == 'small' && !empty($settings['content']) ){ 
				$ret .= '<span class="skill-bar-content" style="color: ' . $settings['text-color'] . ';" >';
				$ret .= $settings['content'];
				$ret .= '</span>';
				$ret .= '<span class="skill-bar-percent" style="color: ' . $settings['text-color'] . ';" >';
				$ret .= esc_attr($settings['percent']) . '%';
				$ret .= '</span>';
			}
			$ret .= '<div class="limoking-skill-bar limoking-ux" style="background-color: ' . $settings['background-color'] . ';" >';
			$ret .= '<div class="limoking-skill-bar-progress" data-percent="' . esc_attr($settings['percent']) . '" ';
			$ret .= 'style="background-color: ' . $settings['progress-color'] . ';" >';
			if( $settings['size'] != 'small' && !empty($settings['content']) ){ 
				$ret .= '<span class="skill-bar-content" style="color: ' . $settings['text-color'] . ';" >';
				$ret .= empty($settings['icon'])? '': '<i class="fa ' . $settings['icon'] . '" ></i>';
				$ret .= $settings['content'];
				$ret .= '</span>';
			}		
			$ret .= '</div>'; // limoking-skill-bar-progress
			$ret .= '</div>'; // limoking-skill-bar
			$ret .= '</div>'; // limoking-skill-bar-wrapper				
			
			return $ret;
		}
	}
	
	// skill round item
	if( !function_exists('limoking_get_skill_item') ){
		function limoking_get_skill_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';

			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';	
			$ret  = '<div class="limoking-skill-item-wrapper limoking-skin-content limoking-item ' . (empty($settings['style'])?'limoking-style-1': 'limoking-' . $settings['style']) . '" ' . $item_id . $margin_style . '>';
			if( !empty($settings['style']) && $settings['style'] == 'style-2' && !empty($settings['icon-class']) ){
				$ret .= '<i class="fa ' . $settings['icon-class'] . '" style="color:' . $settings['icon-color'] .';" ></i>';
			}
			$ret .= '<div class="limoking-skill-item-title" style="' . (empty($settings['title-color'])? '': "color: {$settings['title-color']};") . '">' . $settings['title'] . '</div>';
			if( empty($settings['style']) || $settings['style'] == 'style-1' ){
				$ret .= '<div class="limoking-skill-item-divider" style="' . (empty($settings['title-color'])? '': "border-color: {$settings['title-color']};") . '" ></div>';
			}
			$ret .= '<div class="limoking-skill-item-caption" style="' . (empty($settings['caption-color'])? '': "color: {$settings['caption-color']};") . '">' . $settings['caption'] . '</div>';
			$ret .= '</div>'; // limoking-skill-item-wrapper		
			
			return $ret;
		}
	}	
	
	// price table item
	if( !function_exists('limoking_get_price_table_item') ){
		function limoking_get_price_table_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['price-table'] = empty($settings['price-table'])? array(): $settings['price-table'];
			$list = is_array($settings['price-table'])? $settings['price-table']: json_decode($settings['price-table'], true);
			$ret  = '<div class="limoking-item limoking-price-table-item" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				$best_price = ($tab['gdl-tab-active'] == 'yes')? ' best-price ': '';
				
				$ret .= '<div class="limoking-price-item ' . limoking_get_column_class('1/' . $settings['columns']) . '">';
				$ret .= '<div class="limoking-price-inner-item ' . $best_price . '">';
				
				$ret .= '<div class="price-title-wrapper">';
				$ret .= '<h4 class="price-title">' . limoking_text_filter($tab['gdl-tab-title']) . '</h4>';
				$ret .= '<div class="price-tag">' . limoking_text_filter($tab['gdl-tab-price']) . '</div>';
				$ret .= '</div>';
				
				$ret .= '<div class="price-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				
				if(!empty($tab['gdl-tab-link'])){
					$ret .= '<div class="price-button">';
					$ret .= '<a class="limoking-button without-border" href="' . esc_url($tab['gdl-tab-link']) . '">' . esc_html__('Buy Now', 'limoking') . '</a>';
					$ret .= '</div>';
				}
				
				$ret .= '</div>'; // limoking-price-inner-item
				$ret .= '</div>'; // limoking-price-item
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			return $ret;
		}
	}
	
	// pie chart item
	if( !function_exists('limoking_get_pie_chart_item') ){
		function limoking_get_pie_chart_item( $settings ){	
			global $limoking_spaces;
			wp_enqueue_script('jquery-easypiechart', get_template_directory_uri() . '/plugins/easy-pie-chart/jquery.easy-pie-chart.js', array(), '1.0', true);
			
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="limoking-item limoking-pie-chart-item" ' . $item_id . $margin_style . '>';
			
			$ret .= '<div class="limoking-chart limoking-ux" data-percent="' . esc_attr($settings['progress']) . '" data-size="155" data-linewidth="8" ';
			$ret .= 'data-color="' . esc_attr($settings['color']) . '" data-bg-color="' . esc_attr($settings['bg-color']) . '" >';
			$ret .= '<div class="chart-content-wrapper">';
			$ret .= '<div class="chart-content-inner">';
			$ret .= '<span class="chart-content" ><i class="fa ' . $settings['icon'] . '" ></i></span>';
			$ret .= '<span class="chart-percent-number" style="color:' . $settings['color'] . ';" >' . $settings['progress'] . '%' . '</span>';
			$ret .= '</div>';			
			$ret .= '</div>';			
			$ret .= '</div>';			
			
			$ret .= '<h4 class="pie-chart-title">' . limoking_text_filter($settings['title']) . '</h4>';
			$ret .= '<div class="pie-chart-content">';
			$ret .= limoking_content_filter($settings['content']);
			if( !empty($settings['learn-more-link']) ){
				$ret .= '<a href="' . esc_url($settings['learn-more-link']) . '" ';
				$ret .= 'class="pie-chart-learn-more">' . esc_html__('Learn More', 'limoking') . '</a>';	
			}
			$ret .= '</div>'; // pie-chart-content
			
			$ret .= '</div>'; // limoking-item
			return $ret;
		}
	}
	
	// tab item
	if( !function_exists('limoking_get_tab_item') ){
		function limoking_get_tab_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$tabs = is_array($settings['tab'])? $settings['tab']: json_decode($settings['tab'], true);			
			$current_tab = 0;

			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="limoking-item limoking-tab-item '  . $settings['style'] . '" ' . $item_id . $margin_style . '>';
			$ret .= '<div class="tab-title-wrapper" >';
			foreach( $tabs as $tab ){  $current_tab++;
				$ret .= '<h4 class="tab-title';
				$ret .= ($current_tab == intval($settings['initial-state']))? ' active" ': '" ';
				$ret .= empty($tab['gdl-tab-title-id'])? '>': 'id="' . $tab['gdl-tab-title-id'] . '" >';
				$ret .= empty($tab['gdl-tab-icon-title'])? '': '<i class="fa ' . $tab['gdl-tab-icon-title'] . '" ></i>';				
				$ret .= '<span>' . limoking_text_filter($tab['gdl-tab-title']) . '</span></h4>';				
			}
			$ret .= '</div>';
			
			$current_tab = 0;
			$ret .= '<div class="tab-content-wrapper" >';
			foreach( $tabs as $tab ){  $current_tab++;
				$ret .= '<div class="tab-content';
				$ret .= ($current_tab == intval($settings['initial-state']))? ' active" >': '" >';
				$ret .= limoking_content_filter($tab['gdl-tab-content']) . '</div>';
							
			}	
			$ret .= '</div>';	
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // limoking-tab-item 
			
			return $ret;
		}
	}		
	
	// stunning text item
	if( !function_exists('limoking_get_stunning_text_item') ){
		function limoking_get_stunning_text_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			$margin = (!empty($settings['margin-bottom']) && $settings['margin-bottom'] != 0)? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="limoking-stunning-item-ux limoking-ux">';
			$ret .= '<div class="limoking-item limoking-stunning-item" ' . $item_id . $margin_style . '>';
			$ret .= '<div class="stunning-item-content">';
			$ret .= '<h2 class="stunning-item-title">' . limoking_text_filter($settings['title']) . '</h2>';
			if( !empty($settings['caption']) ){ 
				$ret .= '<div class="stunning-item-caption limoking-skin-content">' . limoking_text_filter($settings['caption']) . '</div>';
			}
			$ret .= '</div>';
			if( !empty($settings['button-link']) || (!empty($settings['read-more-type']) && $settings['read-more-type'] == 'car-form') ){
				$style  = empty($settings['button-text-color'])? '': "color: {$settings['button-text-color']};";
				$style .= empty($settings['button-background'])? '': "background: {$settings['button-background']};";
				$style  = empty($style)? '': "style=\"{$style}\"";
				
				if( empty($settings['read-more-type']) || $settings['read-more-type'] == 'url' ){
					$ret .= '<a class="stunning-item-button limoking-info-font" href="' . esc_url($settings['button-link']) . '" ' . $style . ' >';
					$ret .= $settings['button-text'];
					$ret .= '</a>';	
				}else{
					global $limoking_contact_car; $limoking_contact_car = '';
					
					$button_id = limoking_get_book_now_id();
					$ret .= '<a class="stunning-item-button limoking-info-font" href="#' . $button_id . '" ' . $style . ' data-fancybox-type="inline" data-rel="fancybox">' . $settings['button-text'] . '</a>';
					$ret .= limoking_get_book_now_button();
				}
			}
			$ret .= '</div>'; // limoking-item
			$ret .= '</div>'; // limoking-ux
			
			return $ret;
		}
	}	

	if( !function_exists('limoking_get_divider_item') ){
		function limoking_get_divider_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-divider-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$style = empty($settings['size'])? '': ' style="width: ' . $settings['size'] . ';" ';
			$ret  = '<div class="clear"></div>';
			$ret .= '<div class="limoking-item limoking-divider-item" ' . $item_id . $margin_style . ' >';
			if( $settings['type'] != 'with-icon' ){
				$ret .= '<div class="limoking-divider ' . $settings['type'] . '" ' . $style . '></div>';
			}else{
				$ret .= '<div class="limoking-divider-with-icon" ' . $style . '>';
				$ret .= '<div class="limoking-divider-with-icon-left"></div>';
				$ret .= '<div class="limoking-divider-icon-outer" style="border-color: ' . $settings['divider-color'] . '" >';
				$ret .= '<div class="limoking-divider-icon" style="background-color: ' . $settings['divider-color'] . '" >';
				$ret .= '<i class="fa ' . $settings['icon-class'] . '" ></i>';
				$ret .= '</div>';
				$ret .= '</div>';
				$ret .= '<div class="limoking-divider-with-icon-right"></div>';
				$ret .= '</div>';
			}
			$ret .= '</div>';					
			
			return $ret;
		}
	}
	
	// boxed icon item
	if( !function_exists('limoking_get_box_icon_item') ){
		function limoking_get_box_icon_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="limoking-box-with-icon-ux limoking-ux">';
			$ret .= '<div class="limoking-item limoking-box-with-icon-item pos-' . $settings['icon-position'];
			$ret .=	' type-' . $settings['icon-type'] . '" ' . $item_id . $margin_style . '>';
			
			
			$ret .= ($settings['icon-type'] == 'circle')? '<div class="box-with-circle-icon" style="background-color: ' . $settings['icon-background'] . '">': '';
			$style = empty($settings['icon-color'])? '': ' style="color:' . $settings['icon-color'] . ';" ';
			$ret .= '<i class="fa ' . $settings['icon'] . '" ' . $style . '></i><br>';
			$ret .= ($settings['icon-type'] == 'circle')? '</div>': '';
			
			$ret .= '<h4 class="box-with-icon-title">' . limoking_text_filter($settings['title']) . '</h4>';
			$ret .= '<div class="clear"></div>';
			$ret .= '<div class="box-with-icon-caption">' . limoking_content_filter($settings['content']) . '</div>';
			$ret .= '</div>'; // limoking-item	
			$ret .= '</div>'; // limoking-ux
			
			return $ret;
		}
	}
	
	
	// styled box item
	if( !function_exists('limoking_get_styled_box_item') ){
		function limoking_get_styled_box_item( $settings ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			$style  = 'color: ' . $settings['content-color'] . '; ';
			$style .= empty($settings['height'])? '': 'height: ' . $settings['height'] . '; ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$ret  = '<div class="limoking-styled-box-item-ux limoking-ux" >';
			$ret .= '<div class="limoking-item limoking-styled-box-item" ' . $item_id . $margin_style . '>';
			if($settings['type'] == 'color'){
				if(!empty($settings['flip-corner']) && $settings['flip-corner'] == 'enable'){
					$ret .= '<div class="limoking-styled-box-head-wrapper" >';
					$ret .= '<div class="limoking-styled-box-corner" style="border-bottom-color:' . $settings['corner-color'] . ';" ></div>';
					$ret .= '<div class="limoking-styled-box-head" style="background-color:' . $settings['background-color'] . ';" ></div>';
					$ret .= '</div>';
					$ret .= '<div class="limoking-styled-box-body with-head" style="background-color:' . $settings['background-color'] . '; ' . $style . '" >';
				}else{
					$ret .= '<div class="limoking-styled-box-body" style="background-color:' . $settings['background-color'] . '; ' . $style . '" >';
				}
				
			}else if( $settings['type'] == 'image' ){
				if( is_numeric($settings['background-image']) ){ 
					$thumbnail = wp_get_attachment_image_src($settings['background-image'], 'full');
					$file_url = $thumbnail[0];
				}else{
					$file_url = $settings['background-image'];
				}			
				$ret .= '<div class="limoking-styled-box-body" style="background-image: url(\'' . esc_url($file_url) . '\'); ' . $style . '" >';
			}
			$ret .= limoking_content_filter($settings['content']);
			$ret .= '</div>'; // limoking-styled-box-body
			$ret .= '</div>'; // limoking-item
			$ret .= '</div>'; // limoking-ux
			return $ret;
		}
	}		
	
	// testimonial item
	if( !function_exists('limoking_get_testimonial_item') ){
		function limoking_get_testimonial_item( $settings ){
			if( $settings['testimonial-type'] == 'carousel' ){
				return limoking_get_carousel_testimonial_item($settings);
			}else{
				return limoking_get_static_testimonial_item($settings);
			}
		}
	}		
	if( !function_exists('limoking_get_static_testimonial_item') ){
		function limoking_get_static_testimonial_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';	

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['testimonial'] = empty($settings['testimonial'])? array(): $settings['testimonial'];
			$list = is_array($settings['testimonial'])? $settings['testimonial']: json_decode($settings['testimonial'], true);
			$item_size = intval($settings['testimonial-columns']);
			
			$current_size = 0;

			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="limoking-testimonial-item-wrapper" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				if( $current_size % $item_size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
				
				$ret .= '<div class="' . limoking_get_column_class('1/' . $item_size) . '">';
				$ret .= '<div class="limoking-item limoking-testimonial-item ' . $settings['testimonial-style'] . '">';
				$ret .= '<div class="limoking-ux limoking-testimonial-ux">';
				$ret .= '<div class="testimonial-item">';

				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="testimonial-item-inner limoking-skin-box">';
					$ret .= '<div class="testimonial-item-content-wrapper">';
				}
				
				$ret .= '<div class="testimonial-content limoking-info-font limoking-skin-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				if( strpos($settings['testimonial-style'], 'modern-style') !== false ){
					$ret .= '<div class="testimonial-author-image limoking-skin-border" >';
					$ret .= limoking_get_image($tab['gdl-tab-author-image'], 'thumbnail');
					$ret .= '</div>';
				}
				$ret .= '<div class="testimonial-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<span class="testimonial-author limoking-skin-link-color">' . limoking_text_filter($tab['gdl-tab-title']);
					$ret .= (!empty($tab['gdl-tab-position']))? '<span>, </span>': '';
					$ret .= '</span>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<span class="testimonial-position limoking-skin-info">' . limoking_text_filter($tab['gdl-tab-position']) . '</span>';
				}
				$ret .= '</div>'; // testimonial-info
				
				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					if( strpos($settings['testimonial-style'], 'modern-style') === false ){
						$ret .= '<div class="testimonial-author-image limoking-skin-border" >';
						$ret .= limoking_get_image($tab['gdl-tab-author-image'], 'thumbnail');
						$ret .= '</div>';
					}
					$ret .= '<div class="clear"></div>';
					$ret .= '</div>'; // testimonial-item-inner
					$ret .= '</div>'; // testimonial-item-content-wrapper
				}
				$ret .= '</div>'; // testimonial-item
				$ret .= '</div>'; // limoking-ux
				$ret .= '</div>'; // limoking-item
				$ret .= '</div>'; // limoking-get-column-class
				$current_size ++;
			}
			
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			return $ret;
		}
	}
	if( !function_exists('limoking_get_carousel_testimonial_item') ){
		function limoking_get_carousel_testimonial_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['testimonial'] = empty($settings['testimonial'])? array(): $settings['testimonial'];
			$list = is_array($settings['testimonial'])? $settings['testimonial']: json_decode($settings['testimonial'], true);
			$ret  = '<div class="limoking-testimonial-item-wrapper" ' . $item_id . $margin_style . '>';
			
			$settings['carousel'] = true; 
			$ret .= limoking_get_item_title($settings);							
			$ret .= '<div class="limoking-item limoking-testimonial-item carousel ' . $settings['testimonial-style'] . '">';
			$ret .= '<div class="limoking-ux limoking-testimonial-ux">';
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="limoking-testimonial-item" ';
			$ret .= 'data-columns="' . $settings['testimonial-columns'] . '" >';
			$ret .= '<ul class="slides" >';
			foreach( $list as $tab ){ 
				$ret .= '<li class="testimonial-item">';
				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="testimonial-item-inner limoking-skin-box">';
					$ret .= '<div class="testimonial-item-content-wrapper">';
				}

				$ret .= '<div class="testimonial-content limoking-info-font limoking-skin-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				if( strpos($settings['testimonial-style'], 'modern-style') !== false ){
					$ret .= '<div class="testimonial-author-image limoking-skin-border" >';
					$ret .= limoking_get_image($tab['gdl-tab-author-image'], 'thumbnail');
					$ret .= '</div>';
				}
				$ret .= '<div class="testimonial-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<span class="testimonial-author limoking-skin-link-color">' . limoking_text_filter($tab['gdl-tab-title']);
					$ret .= (!empty($tab['gdl-tab-position']))? '<span>, </span>': '';
					$ret .= '</span>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<span class="testimonial-position limoking-skin-info">' . limoking_text_filter($tab['gdl-tab-position']) . '</span>';
				}
				$ret .= '</div>'; // testimonial-info
				
				if( strpos($settings['testimonial-style'], 'plain-style') === false ){ // hide this in plain style
					if( strpos($settings['testimonial-style'], 'modern-style') === false ){
						$ret .= '<div class="testimonial-author-image limoking-skin-border" >';
						$ret .= limoking_get_image($tab['gdl-tab-author-image'], 'thumbnail');
						$ret .= '</div>';
					}
					$ret .= '<div class="clear"></div>';
					$ret .= '</div>'; // testimonial-item-inner
					$ret .= '</div>'; // testimonial-item-content-wrapper
				}
				$ret .= '</li>';
			}
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // limoking-ux
			$ret .= '</div>'; // limoking-testimonial-item
			
			if( !empty($settings['title-type']) && $settings['title-type'] == 'center' ){ 
				$ret .= limoking_get_item_title(array('carousel'=>true));	
			}
			$ret .= '</div>'; // limoking-testimonial-item-wrapper
			
			return $ret;
		}
	}	
	
	// personnel item
	if( !function_exists('limoking_get_personnel_item') ){
		function limoking_get_personnel_item( $settings ){
			if( $settings['personnel-style'] == 'box-style' ){
				$settings['thumbnail-size'] == 'thumbnail';
			}
		
			if( $settings['personnel-type'] == 'carousel' ){
				return limoking_get_carousel_personnel_item($settings);
			}else{
				return limoking_get_static_personnel_item($settings);
			}
		}
	}		
	if( !function_exists('limoking_get_static_personnel_item') ){
		function limoking_get_static_personnel_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';			
			
			$settings['personnel'] = empty($settings['personnel'])? array(): $settings['personnel'];
			$list = is_array($settings['personnel'])? $settings['personnel']: json_decode($settings['personnel'], true);
			$item_size = intval($settings['personnel-columns']);
			
			$current_size = 0; 
			
			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="limoking-personnel-item-wrapper" ' . $item_id . $margin_style . '>';
			foreach( $list as $tab ){ 
				if( $current_size % $item_size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
				
				$ret .= '<div class="' . limoking_get_column_class('1/' . $item_size) . '">';
				$ret .= '<div class="limoking-item limoking-personnel-item ' . $settings['personnel-style'] . '">';
				$ret .= '<div class="limoking-ux limoking-personnel-ux">';
				$ret .= '<div class="personnel-item">';
				
				if( $settings['personnel-style'] == 'round-style' ){
					$ret .= '<div class="personnel-author-image" >';
					$ret .= limoking_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';	
				}
				
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="personnel-item-inner limoking-skin-box">';
				}
				if( $settings['personnel-style'] != 'round-style' ){
					$ret .= '<div class="personnel-author-image limoking-skin-border" >';
					$ret .= limoking_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';		
				}
				
				$ret .= '<div class="personnel-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<div class="personnel-author limoking-skin-title">' . limoking_text_filter($tab['gdl-tab-title']) . '</div>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<div class="personnel-position limoking-info-font limoking-skin-info">' . limoking_text_filter($tab['gdl-tab-position']) . '</div>';
				}
				$ret .= '</div>'; // personnel-info
				
				if( !empty($tab['gdl-tab-content']) ){
					$ret .= '<div class="personnel-content limoking-skin-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				}
				
				if( !empty($tab['gdl-tab-social-list']) ){
					$ret .= '<div class="personnel-social">';
					$ret .= limoking_text_filter($tab['gdl-tab-social-list']);
					$ret .= '</div>';
				}
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '</div>'; // personnel-item-inner
				}
				
				$ret .= '</div>'; // personnel-item
				$ret .= '</div>'; // limoking-ux
				$ret .= '</div>'; // limoking-item
				$ret .= '</div>'; // limoking-get-column-class
				$current_size ++;
			}
			
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			return $ret;
		}
	}
	if( !function_exists('limoking_get_carousel_personnel_item') ){
		function limoking_get_carousel_personnel_item( $settings ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
			
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			$settings['carousel'] = true;
			$settings['personnel'] = empty($settings['personnel'])? array(): $settings['personnel'];
			$list = is_array($settings['personnel'])? $settings['personnel']: json_decode($settings['personnel'], true);

			$ret  = '<div class="limoking-personnel-item-wrapper" ' . $item_id . $margin_style . '>';
			$ret .= limoking_get_item_title($settings);		
			$ret .= '<div class="limoking-item limoking-personnel-item carousel ' . $settings['personnel-style'] . '">';
			$ret .= '<div class="limoking-ux limoking-personnel-ux">';
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="limoking-personnel-item" ';
			$ret .= 'data-columns="' . $settings['personnel-columns'] . '" >';
			$ret .= '<ul class="slides" >';
			foreach( $list as $tab ){ 
				$ret .= '<li class="personnel-item">';
				
				if( $settings['personnel-style'] == 'round-style' ){
					$ret .= '<div class="personnel-author-image" >';
					$ret .= limoking_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';	
				}				
				
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '<div class="personnel-item-inner limoking-skin-box">';
				}
				
				if( $settings['personnel-style'] != 'round-style' ){
					$ret .= '<div class="personnel-author-image limoking-skin-border" >';
					$ret .= limoking_get_image($tab['gdl-tab-author-image'], $settings['thumbnail-size']);
					$ret .= '</div>';				
				}

				$ret .= '<div class="personnel-info">';
				if( !empty($tab['gdl-tab-title'] ) ){
					$ret .= '<div class="personnel-author limoking-skin-title">' . limoking_text_filter($tab['gdl-tab-title']) . '</div>';
				}
				if( !empty($tab['gdl-tab-position']) ){
					$ret .= '<div class="personnel-position limoking-info-font limoking-skin-info">' . limoking_text_filter($tab['gdl-tab-position']) . '</div>';
				}
				$ret .= '</div>'; // personnel-info
				
				if( !empty($tab['gdl-tab-content']) ){
					$ret .= '<div class="personnel-content limoking-skin-content">' . limoking_content_filter($tab['gdl-tab-content']) . '</div>';
				}
				
				if( !empty($tab['gdl-tab-social-list']) ){
					$ret .= '<div class="personnel-social">';
					$ret .= limoking_text_filter($tab['gdl-tab-social-list']);
					$ret .= '</div>';
				}
				if( strpos($settings['personnel-style'], 'plain-style') === false ){ // hide this in plain style
					$ret .= '</div>'; // personnel-item-inner
				}
				$ret .= '</li>';
			}
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // limoking-ux
			$ret .= '</div>'; // limoking-personnel-item
			$ret .= '</div>'; // limoking-personnel-item-wrapper
			
			return $ret;
		}
	}			
	
	// page list item
	if( !function_exists('limoking_get_page_list_item') ){
		function limoking_get_page_list_item( $settings ){	
			if(function_exists('limoking_include_portfolio_scirpt')){ limoking_include_portfolio_scirpt(); }
		
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';
		
			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';

			$ret  = limoking_get_item_title($settings);	
			$ret .= '<div class="portfolio-item-wrapper type-' . $settings['page-style'] . '" ' . $item_id . $margin_style . '>'; 
			
			// query section
			$args = array('post_type' => 'page', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = 'menu_order';
			$args['order'] = 'asc';
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : get_query_var('page');
			$args['paged'] = empty($args['paged'])? 1: $args['paged'];
			if( !empty($settings['category']) ){
				$args['tax_query'] = array( 
					array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'page_category', 'field'=>'slug')
				);		
			}		

			$query = new WP_Query( $args );	
				
			// print item section
			$settings['item-size'] = str_replace('1/', '', $settings['item-size']);
			
			$ret .= '<div class="portfolio-item-holder">';
			if($settings['page-style'] == 'classic'){
				$ret .= limoking_get_classic_page_list($query, $settings['item-size'], 
							$settings['thumbnail-size'], $settings['page-layout'] );
			}else if($settings['page-style'] == 'modern'){	
				$ret .= limoking_get_modern_page_list($query, $settings['item-size'], 
							$settings['thumbnail-size'], $settings['page-layout'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';	

			if($settings['pagination'] == 'enable'){
				$ret .= limoking_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // portfolio-item-wrapper
			return $ret;
			
		}
	}
	
	// print classic page list
	if( !function_exists('limoking_get_classic_page_list') ){
		function limoking_get_classic_page_list($query, $size, $thumbnail_size, $layout = 'fitRows'){
			$current_size = 0;
			$ret  = '<div class="limoking-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . limoking_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="limoking-item limoking-portfolio-item limoking-classic-portfolio">';
				
				$ret .= '<div class="portfolio-thumbnail limoking-image">';
				$ret .= limoking_get_image(get_post_thumbnail_id(), $thumbnail_size);
				$ret .= '<a class="portfolio-overlay-wrapper" href="' . get_permalink() . '" >';
				$ret .= '<span class="portfolio-overlay" ></span>';
				$ret .= '<span class="portfolio-icon" ><i class="fa ' . limoking_fa_class('icon-link') . '" ></i></span>';
				$ret .= '</a>';	
				$ret .= '</div>'; // portfolio-thumbnail
 
				$ret .= '<div class="portfolio-content-wrapper">';
				$ret .= '<h3 class="portfolio-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</div>';
				
				$ret .= '</div>';				
				$ret .= '</div>';
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	

	// print modern page list
	if( !function_exists('limoking_get_modern_page_list') ){
		function limoking_get_modern_page_list($query, $size, $thumbnail_size, $layout = 'fitRows'){
			$current_size = 0;
			$ret  = '<div class="limoking-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
    
				$ret .= '<div class="' . limoking_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="limoking-item limoking-portfolio-item limoking-modern-portfolio">';
				
				// overlay
				$ret .= '<div class="portfolio-thumbnail limoking-image">';
				$ret .= limoking_get_image(get_post_thumbnail_id(), $thumbnail_size);
				$ret .= '<a class="portfolio-overlay-wrapper" href="' . get_permalink() . '" >';
				$ret .= '<span class="portfolio-overlay" >';
				$ret .= '<span class="portfolio-icon" ><i class="fa ' . limoking_fa_class('icon-link') . '" ></i></span>';
				$ret .= '</span>';
				$ret .= '<div class="portfolio-thumbnail-bar"></div>';
				$ret .= '</a>';	
				
				// content
				$ret .= '<div class="portfolio-content-wrapper">';
				$ret .= '<div class="portfolio-content-overlay"></div>';
				$ret .= '<h3 class="portfolio-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</div>'; // portfolio-content-wrapper
				$ret .= '</div>'; // portfolio-thumbnail	
				
				$ret .= '</div>'; // limoking-item				
				$ret .= '</div>'; // column class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}		
?>