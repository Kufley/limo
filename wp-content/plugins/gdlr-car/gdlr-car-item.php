<?php
	/*	
	*	Goodlayers Car Item Management File
	*	---------------------------------------------------------------------
	*	This file contains functions that help you create car item
	*	---------------------------------------------------------------------
	*/
	
	// add action to check for car item
	add_action('limoking_print_item_selector', 'limoking_check_car_item', 10, 2);
	if( !function_exists('limoking_check_car_item') ){
		function limoking_check_car_item( $type, $settings = array() ){
			if($type == 'car'){
				echo limoking_print_car_item( $settings );
			}else if($type == 'car-rate'){
				echo limoking_print_car_rate_item( $settings );
			}
		}
	}

	// include car script
	if( !function_exists('limoking_include_car_scirpt') ){
		function limoking_include_car_scirpt( $settings = array() ){
			wp_enqueue_script('isotope', get_template_directory_uri() . '/plugins/jquery.isotope.min.js', array(), '1.0', true);
			wp_enqueue_script('jquery.transit', get_template_directory_uri() . '/plugins/jquery.transit.min.js', array(), '1.0', true);	
			wp_enqueue_script('car-script', plugins_url('gdlr-car-script.js', __FILE__), array(), '1.0', true);			
		}
	}
	
	// print car rate item
	if( !function_exists('limoking_print_car_rate_item') ){
		function limoking_print_car_rate_item( $settings = array() ){	
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $theme_option, $limoking_spaces, $limoking_contact_car, $limoking_trip_type;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';		
		
			$presets = array(
				'hour-rate' => array(
					'title'=> __('Per Hour Rate', 'gdlr-car'),
					'caption-text'=> (empty($theme_option['rate-table-per-hour-caption'])? '': $theme_option['rate-table-per-hour-caption']),
					'rate-text'=> __('/ Hour', 'gdlr-car'),
					'rate'=> 'per-hour-rate-amount',
					'caption'=> 'per-hour-rate-caption',
					'feature'=>($settings['feature-rate'] == 'hour-rate')
				),				
				'day-rate' => array(
					'title'=> __('Per Day Rate', 'gdlr-car'),
					'caption-text'=> (empty($theme_option['rate-table-per-day-caption'])? '': $theme_option['rate-table-per-day-caption']),
					'rate-text'=> __('/ Day', 'gdlr-car'),
					'rate'=> 'per-day-rate-amount',
					'caption'=> 'per-day-rate-caption',
					'feature'=>($settings['feature-rate'] == 'day-rate')
				),				
				'airport-transfer' => array(
					'title'=> __('Airport Transfer', 'gdlr-car'),
					'caption-text'=> (empty($theme_option['rate-table-airport-transfer'])? '': $theme_option['rate-table-airport-transfer']),
					'rate-text'=> '',
					'rate'=> 'airport-transfer-amount',
					'caption'=> 'airport-transfer-caption',
					'feature'=>($settings['feature-rate'] == 'airport-transfer')
				)
			);
		
			// query posts section
			$args = array('post_type' => 'car', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = 1;
			if( !empty($settings['category']) ){
				$args['tax_query'] = array(array(
					'terms'=>explode(',', $settings['category']), 'taxonomy'=>'car_category', 'field'=>'slug'
				));
			}			
			$query = new WP_Query( $args );	
			$limoking_contact_car = $query;
			
			$ret  = '<div class="limoking-rate-table-wrapper limoking-item" ' . $item_id . $margin_style . ' >';
			$ret .= '<div class="limoking-rate-table-head-wrapper" >';
			$ret .= '<div class="limoking-rate-table-column limoking-col-1"></div>';
			$ret .= '<div class="limoking-rate-table-column limoking-col-2">';
			foreach( $presets as $preset ){
				$ret .= '<div class="limoking-rate-column ' . ($preset['feature']? 'limoking-feature': '') . '" >';
				$ret .= '<div class="limoking-rate-table-column-inner" >';
				$ret .= '<div class="rate-table-title limoking-title-font">' . $preset['title'] . '</div>';
				$ret .= '<div class="rate-table-caption limoking-title-font">' . $preset['caption-text'] . '</div>';
				$ret .= '<div class="rate-table-ribbon"></div>';
				$ret .= '</div>';
				$ret .= '</div>';
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // limoking-col-2
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>'; // rate-table-head-wrapper
			
			$ret .= '<div class="limoking-rate-table-content-wrapper">';
			while( $query->have_posts() ){ $query->the_post();
				$car_option = json_decode(limoking_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true)), true);
				
				$ret .= '<div class="limoking-rate-table-content-row" >';
				$ret .= '<div class="limoking-rate-table-column limoking-col-1">';
				$image_id = get_post_thumbnail_id();
				if( !empty($image_id) ){
					$ret .= '<div class="rate-table-car-image">' . limoking_get_image($image_id, $settings['thumbnail-size']) . '</div>';
				}
				$ret .= '<h3 class="rate-table-car-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</div>';
				
				$ret .= '<div class="limoking-rate-table-column limoking-col-2">';
				foreach( $presets as $preset ){
					$ret .= '<div class="limoking-rate-column ' . ($preset['feature']? 'limoking-feature': '') . '" >';
					if( !empty($car_option[$preset['rate']]) && !empty($car_option[$preset['caption']]) ){
						if( !empty($car_option[$preset['rate']]) ){
							$ret .= '<div class="rate-table-price">' . $car_option[$preset['rate']] . ' <span class="rate-table-price-text">' . $preset['rate-text'] . '</span></div>';
						}
						if( !empty($car_option[$preset['caption']]) ){
							$ret .= '<div class="rate-table-price-caption">' . $car_option[$preset['caption']] . '</div>';
						}
					}else{
						$ret .= '<div class="rate-table-price rate-table-price-none">-</div>';
						$ret .= '<div class="rate-table-price-caption">' . __('* Not Available') . '</div>';
					}
					$ret .= '</div>';
				}
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>'; // limoking-col-2
				$ret .= '<div class="clear"></div>';				
				$ret .= '</div>'; // rate-table-content-row
			}
			wp_reset_query();
			$ret .= '</div>'; // rate-table-content-wrapper
			
			if( $button_id = limoking_get_book_now_id() ){
				$ret .= '<div class="limoking-rate-table-button-wrapper">';
				$ret .= '<div class="limoking-rate-table-column limoking-col-1"></div>';
				$ret .= '<div class="limoking-rate-table-column limoking-col-2">';
				$ret .= '<div class="limoking-rate-button ' . ($presets['hour-rate']['feature']? 'limoking-feature': '') . '" >';
				$ret .= '<a class="rate-table-book-now" href="#' . $button_id . '" data-fancybox-type="inline" data-rel="fancybox" >' . __('Book Now', 'gdlr-car') . '</a>';
				$limoking_trip_type = 1;
				echo limoking_get_book_now_button();
				$ret .= '</div>';	
				
				$ret .= '<div class="limoking-rate-button ' . ($presets['day-rate']['feature']? 'limoking-feature': '') . '" >';
				$ret .= '<a class="rate-table-book-now" href="#' . limoking_get_book_now_id() . '" data-fancybox-type="inline" data-rel="fancybox" >' . __('Book Now', 'gdlr-car') . '</a>';
				$limoking_trip_type = 2;
				echo limoking_get_book_now_button();
				$ret .= '</div>';			
				
				$ret .= '<div class="limoking-rate-button ' . ($presets['airport-transfer']['feature']? 'limoking-feature': '') . '" >';
				$ret .= '<a class="rate-table-book-now" href="#' . limoking_get_book_now_id() . '" data-fancybox-type="inline" data-rel="fancybox" >' . __('Book Now', 'gdlr-car') . '</a>';
				$limoking_trip_type = 3;
				echo limoking_get_book_now_button();
				$ret .= '</div>';
				$ret .= '</div>'; // limoking-col-2
				$ret .= '</div>'; // rate-table-button-wrapper
			}
			
			$ret .= '</div>'; // rate-table-wrapper
			
			$query->rewind_posts();
			$ret .= limoking_print_car_rate_mobile_item( $settings, $query, $presets, $margin_style );
			
			return $ret;
		}
	}
	
	// print car rate item
	if( !function_exists('limoking_print_car_rate_mobile_item') ){
		function limoking_print_car_rate_mobile_item( $settings, $query, $presets, $margin_style ){
			global $limoking_trip_type;
			$limoking_trip_type = 0;
			
			$ret  = '<div class="limoking-rate-table-mobile-wrapper limoking-item" ' . $margin_style . ' >';
			foreach( $presets as $key => $preset ){
				$ret .= '<div class="limoking-rate-table-head-wrapper" >';
				$ret .= '<div class="limoking-rate-table-column limoking-col-1"></div>';
				$ret .= '<div class="limoking-rate-table-column limoking-col-2">';
				
				$ret .= '<div class="limoking-rate-column ' . ($preset['feature']? 'limoking-feature': '') . '" >';
				$ret .= '<div class="limoking-rate-table-column-inner" >';
				$ret .= '<div class="rate-table-title limoking-title-font">' . $preset['title'] . '</div>';
				$ret .= '<div class="rate-table-caption limoking-title-font">' . $preset['caption-text'] . '</div>';
				$ret .= '<div class="rate-table-ribbon"></div>';
				$ret .= '</div>';
				$ret .= '</div>';
				
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>'; // limoking-col-2
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>'; // rate-table-head-wrapper
				
				$ret .= '<div class="limoking-rate-table-content-wrapper">';
				while( $query->have_posts() ){ $query->the_post();
					$car_option = json_decode(limoking_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true)), true);
					
					$ret .= '<div class="limoking-rate-table-content-row" >';
					$ret .= '<div class="limoking-rate-table-column limoking-col-1">';
					$image_id = get_post_thumbnail_id();
					if( !empty($image_id) ){
						$ret .= '<div class="rate-table-car-image">' . limoking_get_image($image_id, $settings['thumbnail-size']) . '</div>';
					}
					$ret .= '<h3 class="rate-table-car-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
					$ret .= '</div>';
					
					$ret .= '<div class="limoking-rate-table-column limoking-col-2">';
					$ret .= '<div class="limoking-rate-column ' . ($preset['feature']? 'limoking-feature': '') . '" >';
					if( !empty($car_option[$preset['rate']]) && !empty($car_option[$preset['caption']]) ){
						if( !empty($car_option[$preset['rate']]) ){
							$ret .= '<div class="rate-table-price">' . $car_option[$preset['rate']] . ' <span class="rate-table-price-text">' . $preset['rate-text'] . '</span></div>';
						}
						if( !empty($car_option[$preset['caption']]) ){
							$ret .= '<div class="rate-table-price-caption">' . $car_option[$preset['caption']] . '</div>';
						}
					}else{
						$ret .= '<div class="rate-table-price rate-table-price-none">-</div>';
						$ret .= '<div class="rate-table-price-caption">' . __('* Not Available') . '</div>';
					}
					$ret .= '</div>';
					$ret .= '<div class="clear"></div>';
					$ret .= '</div>'; // limoking-col-2
					$ret .= '<div class="clear"></div>';				
					$ret .= '</div>'; // rate-table-content-row
				}
				$query->rewind_posts();
				$ret .= '</div>'; // rate-table-content-wrapper
				
				if( $button_id = limoking_get_book_now_id() ){
					$ret .= '<div class="limoking-rate-table-button-wrapper">';
					$ret .= '<div class="limoking-rate-table-column limoking-col-1"></div>';
					$ret .= '<div class="limoking-rate-table-column limoking-col-2">';
					$ret .= '<div class="limoking-rate-button ' . ($presets[$key]['feature']? 'limoking-feature': '') . '" >';
					$ret .= '<a class="rate-table-book-now" href="#' . $button_id . '" data-fancybox-type="inline" data-rel="fancybox" >' . __('Book Now', 'gdlr-car') . '</a>';
					$limoking_trip_type++;
					echo limoking_get_book_now_button();
					$ret .= '</div>';			
					$ret .= '</div>'; // limoking-col-2
					$ret .= '</div>'; // rate-table-button-wrapper
				}
			}
			
			$ret .= '</div>'; // rate-table-mobile-wrapper
			return $ret;
		}
	}	
	
	// print car item
	if( !function_exists('limoking_print_car_item') ){
		function limoking_print_car_item( $settings = array() ){
			limoking_include_car_scirpt();
		
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			if( $settings['car-layout'] == 'carousel' ){ 
				$settings['carousel'] = true;
			}
			
			$ret  = limoking_get_item_title($settings);				
			$ret .= '<div class="car-item-wrapper type-' . $settings['car-style'] . '" ';
			$ret .= $item_id . $margin_style . ' data-ajax="' . AJAX_URL . '" >'; 
			
			// query posts section
			$args = array('post_type' => 'car', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : 1;

			if( !empty($settings['category']) || (!empty($settings['tag']) && $settings['car-filter'] == 'disable') ){
				$args['tax_query'] = array('relation' => 'OR');
				
				if( !empty($settings['category']) ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'car_category', 'field'=>'slug'));
				}
				if( !empty($settings['tag']) && $settings['car-filter'] == 'disable' ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['tag']), 'taxonomy'=>'car_tag', 'field'=>'slug'));
				}				
			}			
			$query = new WP_Query( $args );

			// create the car filter
			$settings['num-excerpt'] = empty($settings['num-excerpt'])? 0: $settings['num-excerpt'];
			$settings['car-size'] = str_replace('1/', '', $settings['car-size']);
			$settings['thumbnail-size-featured'] = empty($settings['thumbnail-size-featured'])? $settings['thumbnail-size']: $settings['thumbnail-size-featured'];
			if( $settings['car-filter'] == 'enable' ){
			
				// ajax infomation
				$ret .= '<div class="limoking-ajax-info" data-num-fetch="' . $args['posts_per_page'] . '" data-num-excerpt="' . $settings['num-excerpt'] . '" ';
				$ret .= 'data-orderby="' . $args['orderby'] . '" data-order="' . $args['order'] . '" data-thumbnail-size-featured="' . $settings['thumbnail-size-featured'] . '" ';
				$ret .= 'data-thumbnail-size="' .  $settings['thumbnail-size'] . '" data-car-style="' . $settings['car-style'] . '" ';
				$ret .= 'data-car-size="' . $settings['car-size'] . '" data-car-layout="' .  $settings['car-layout'] . '" data-price-display="' . $settings['car-price-display'] . '" ';
				$ret .= 'data-ajax="' . admin_url('admin-ajax.php') . '" data-category="' . $settings['category'] . '" data-pagination="' . $settings['pagination'] . '" ></div>';
			
				// category filter
				if( empty($settings['category']) ){
					$parent = array('limoking-all'=>__('All', 'gdlr-car'));
					$settings['category-id'] = '';
				}else{
					$term = get_term_by('slug', $settings['category'], 'car_category');
					$parent = array($settings['category']=>$term->name);
					$settings['category-id'] = $term->term_id;
				}
				
				$filters = $parent + limoking_get_term_list('car_category', $settings['category-id']);
				$filter_active = 'active';
				$ret .= '<div class="car-item-filter">';
				foreach($filters as $filter_id => $filter){
					$filter_id = ($filter_id == 'limoking-all')? '': $filter_id;
					$ret .= '<span class="limoking-separator" >/</span>';
					$ret .= '<a class="' . $filter_active . '" href="#" ';
					$ret .= 'data-category="' . $filter_id . '" >' . $filter . '</a>';
					$filter_active = '';
				}
				$ret .= '</div>';
			}
			
			$column_size = ' limoking-car-column-' . $settings['car-size'];
			$ret .= '<div class="car-item-holder ' . $column_size . '">';
			if( $settings['car-style'] == 'classic-car' ){
				
				global $limoking_excerpt_length; $limoking_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'limoking_set_excerpt_length');
				
				$ret .= limoking_get_classic_car($query, $settings['car-size'], 
							$settings['thumbnail-size'], $settings['car-layout'], $settings['car-price-display'] );
							
				remove_filter('excerpt_length', 'limoking_set_excerpt_length');
			}else if($settings['car-style'] == 'modern-car' ){	
				
				$ret .= limoking_get_modern_car($query, $settings['car-size'], 
							$settings['thumbnail-size'], $settings['car-layout'], $settings['thumbnail-size-featured'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// create pagination
			if($settings['car-filter'] == 'enable' && $settings['pagination'] == 'enable'){
				$ret .= limoking_get_ajax_pagination($query->max_num_pages, $args['paged']);
			}else if($settings['pagination'] == 'enable'){
				$ret .= limoking_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // car-item-wrapper
			return $ret;
		}
	}
	
	// ajax function for car filter / pagination
	add_action('wp_ajax_limoking_get_car_ajax', 'limoking_get_car_ajax');
	add_action('wp_ajax_nopriv_limoking_get_car_ajax', 'limoking_get_car_ajax');
	if( !function_exists('limoking_get_car_ajax') ){
		function limoking_get_car_ajax(){
			$settings = $_POST['args'];

			$args = array('post_type' => 'car', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (empty($settings['paged']))? 1: $settings['paged'];
				
			if( !empty($settings['category']) ){
				$args['tax_query'] = array(
					array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'car_category', 'field'=>'slug')
				);
			}			
			$query = new WP_Query( $args );
			
			$column_size = ' limoking-car-column-' . $settings['car-size'];
			$ret  = '<div class="car-item-holder ' . $column_size . '">';
			if( $settings['car-style'] == 'classic-car' ){
				
				global $limoking_excerpt_length; $limoking_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'limoking_set_excerpt_length');
				
				$ret .= limoking_get_classic_car($query, $settings['car-size'], 
							$settings['thumbnail-size'], $settings['car-layout'], $settings['car-price-display'] );
							
				remove_filter('excerpt_length', 'limoking_set_excerpt_length');
			}else if($settings['car-style'] == 'modern-car' ){	
				
				$ret .= limoking_get_modern_car($query, $settings['car-size'], 
							$settings['thumbnail-size'], $settings['car-layout'], $settings['thumbnail-size-featured'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// pagination section
			if($settings['pagination'] == 'enable'){
				$ret .= limoking_get_ajax_pagination($query->max_num_pages, $args['paged']);
			}
			die($ret);
		}
	}
	
	// get car info
	if( !function_exists('limoking_get_car_info') ){
		function limoking_get_car_info( $post_option, $max_display = 9999 ){
			$ret = '';
			$count = 1;
			
			if( !empty($post_option['car-info']) ){ 
				$car_infos = json_decode($post_option['car-info'], true);
				$ret = '<div class="limoking-car-info-inner">';
				foreach( $car_infos as $car_info ){ 
					$ret .= '<div class="limoking-car-info">';
					$ret .= "<i class=\"limoking-car-info-icon fa {$car_info['icon']}\" ></i>";
					$ret .= "<span class=\"limoking-car-info-value\" >{$car_info['value']}</span>";
					$ret .= '</div>';
					
					if( $count >= $max_display ) break;
					$count++;
				}
				$ret .= '</div>';
			}
			return $ret;
		}
	}

	// get car thumbnail class
	if( !function_exists('limoking_get_car_thumbnail_class') ){
		function limoking_get_car_thumbnail_class( $post_option ){
			global $limoking_related_section;

			switch($post_option['inside-thumbnail-type']){
				case 'feature-image': return 'limoking-image' ;
				case 'image': return 'limoking-image' ;
				case 'video': return 'limoking-video' ;
				case 'slider': return 'limoking-slider' ;		
				case 'stack-images': return 'limoking-stack-images' ;
				default: return '';
			}			
		}
	}
	
	if( !function_exists('gdlr_get_car_gallery') ){
		function gdlr_get_car_gallery( $slider_option ){
			$slider_option = json_decode($slider_option, true);
			$slide_order = $slider_option[0];
			$slide_data = $slider_option[1];					
			
			$slides = array();
			foreach( $slide_order as $slide_id ){
				$slides[$slide_id] = $slide_data[$slide_id];
			}

			$ret  = '<div class="limoking-gallery-item limoking-item limoking-gallery-thumbnail" >';

			// full image
			$ret .= '<div class="limoking-gallery-thumbnail-container">';
			foreach( $slides as $slide_id => $slide ){
				$ret .= '<div class="limoking-gallery-thumbnail" data-id="' . $slide_id . '" >';
				$ret .= limoking_get_image($slide_id);
				$ret .= '</div>';
			}
			$ret .= '</div>';
			
			// start printing gallery
			$current_size = 0;
			foreach( $slides as $slide_id => $slide ){
				$ret .= '<div class="gallery-column">';
				$ret .= '<div class="gallery-item" data-id="' . $slide_id . '" >';
				$ret .= limoking_get_image($slide_id, 'thumbnail');
				$ret .= '</div>'; // gallery item
				$ret .= '</div>'; // gallery column
				$current_size ++;
			}
			$ret .= '<div class="clear"></div>';
			
			$ret .= '</div>'; // limoking-gallery-item
			
			return $ret;
		}
	}
	
	// get car thumbnail
	if( !function_exists('limoking_get_classic_car_rate') ){
		function limoking_get_classic_car_rate($post_option, $type = 'none'){
			$ret  = '';
			if( $type != 'none' && !empty($post_option[$type]) ){
				$ret .= '<span class="car-rate-info-amount" >';
				$ret .= '<span class="car-rate-info-price" ><i class="fa fa-tag" ></i>';
				$ret .= limoking_escape_content($post_option[$type]);
				$ret .= '</span>';
				if( $type == 'per-hour-rate-amount' ){
					$ret .= __('/ Hour', 'gdlr-car');
				}else if( $type == 'per-day-rate-amount' ){
					$ret .= __('/ Day', 'gdlr-car');
				} 
				$ret .= '</span>';
			}
			
			return $ret;
		}
	}
	
	// get car thumbnail
	if( !function_exists('limoking_get_car_feature_image') ){
		function limoking_get_car_feature_image($size = 'full'){
			$image_id = get_post_thumbnail_id();
			if( !empty($image_id) ){
				$ret  = limoking_get_image($image_id, $size);
				$ret .= '<span class="car-overlay" >&nbsp;</span>';
				$ret .= '<a class="car-overlay-icon" href="' . get_permalink() . '" >';
				$ret .= '<span class="car-icon" ><i class="fa fa-link" ></i></span>';
				$ret .= '</a>';	
			}
			return $ret;
			
		}
	}
	
	// get car thumbnail
	if( !function_exists('limoking_get_car_thumbnail') ){
		function limoking_get_car_thumbnail($post_option, $size = 'full'){
			$ret = '';
			switch($post_option['inside-thumbnail-type']){
				case 'feature-image':
					$image_id = get_post_thumbnail_id();
					if( !empty($image_id) ){
						$ret  = limoking_get_image($image_id, $size, true);
					}
					break;			
				case 'image':
					$ret = limoking_get_image($post_option['inside-thumbnail-image'], $size, true);
					break;
				case 'video': 
					if( is_single() ){
						$ret = limoking_get_video($post_option['inside-thumbnail-video'], 'full');
					}else{
						$ret = limoking_get_video($post_option['inside-thumbnail-video'], $size);
					}
					break;
				case 'slider': 
					$ret = gdlr_get_car_gallery($post_option['inside-thumbnail-slider']);
					break;					
				case 'stack-image': 
					$ret = limoking_get_stack_images($post_option['inside-thumbnail-slider']);
					break;
				default :
					$ret = '';
			}			

			return $ret;
		}
	}	
	
	// print classic car
	if( !function_exists('limoking_get_classic_car') ){
		function limoking_get_classic_car($query, $size, $thumbnail_size, $layout = 'fitRows', $car_price_display = 'none'){
			if($layout == 'carousel'){ 
				return limoking_get_classic_carousel_car($query, $size, $thumbnail_size, $car_price_display); 
			}		
		
			global $post;

			$current_size = 0;
			$ret  = '<div class="limoking-isotope" data-type="car" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . limoking_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="limoking-item limoking-car-item limoking-classic-car">';
				$ret .= '<div class="limoking-ux limoking-classic-car-ux">';
				
				$car_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="car-thumbnail">';
				$ret .= limoking_get_car_feature_image($thumbnail_size);
				$ret .= '</div>'; // car-thumbnail
				
				$ret .= '<div class="car-classic-content">';
				$ret .= '<h3 class="car-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= limoking_get_car_info($car_option, 4);
				$ret .= '</div>'; // car-classic-content
				
				$ret .= '<div class="car-classic-button-wrapper" >';
				$ret .= limoking_get_classic_car_rate($car_option, $car_price_display);
				$ret .= '<a class="limoking-car-button" href="' . get_permalink() . '" >' . __('View Details', 'gdlr-car') . '</a>';
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>';
				$ret .= '</div>'; // limoking-ux
				$ret .= '</div>'; // limoking-item
				$ret .= '</div>'; // column class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('limoking_get_classic_carousel_car') ){
		function limoking_get_classic_carousel_car($query, $size, $thumbnail_size, $car_price_display){	
			global $post;

			$ret  = '<div class="limoking-car-carousel-item limoking-item" >';	
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="car-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="limoking-item limoking-car-item limoking-classic-car">';

				$car_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="car-thumbnail">';
				$ret .= limoking_get_car_feature_image($thumbnail_size);
				$ret .= '</div>'; // car-thumbnail
				
				$ret .= '<div class="car-classic-content">';
				$ret .= limoking_get_car_info($car_info, 4);
				$ret .= '</div>';
				
				$ret .= '<div class="car-classic-button-wrapper" >';
				$ret .= limoking_get_classic_car_rate($car_option, $car_price_display);
				$ret .= '<a class="limoking-car-button" href="' . get_permalink() . '" >' . __('View Details', 'gdlr-car') . '</a>';
				$ret .= '<div class="clear"></div>';
				$ret .= '</div>';
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>';
			$ret .= '</div>';
			
			return $ret;
		}		
	}	
	
	// print modern car
	if( !function_exists('limoking_get_modern_car') ){
		function limoking_get_modern_car($query, $size, $thumbnail_size, $layout = 'fitRows', $thumbnail_size_featured = 'full'){
			if($layout == 'carousel'){ 
				return limoking_get_modern_carousel_car($query, $size, $thumbnail_size); 
			}else if($layout == 'masonry-style-1'){
				$layout = 'masonry';
				$featured_post = array(0);
			}else if($layout == 'masonry-style-2'){
				$layout = 'masonry';
				$featured_post = array(0,4,6,7,11,15,17,18);
			}
			
			global $post;

			$current_size = 0;
			$ret  = '<div class="limoking-isotope" data-type="car" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
    
				$ret .= '<div class="' . limoking_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="limoking-item limoking-car-item limoking-modern-car">';
				$ret .= '<div class="limoking-ux limoking-modern-car-ux">';
				
				$car_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="car-thumbnail">';
				if( !empty($featured_post) && in_array($current_size, $featured_post) ){
					$ret .= limoking_get_car_feature_image($thumbnail_size_featured);
				}else{
					$ret .= limoking_get_car_feature_image($thumbnail_size);
				}
				$ret .= '</div>'; // car-thumbnail	
				
				$ret .= '<h3 class="car-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</div>'; // limoking-ux
				$ret .= '</div>'; // limoking-item
				$ret .= '</div>'; // limoking-column-class
				$current_size ++;
			}
			$ret .= '</div>';
			wp_reset_postdata();
			
			return $ret;
		}
	}	
	if( !function_exists('limoking_get_modern_carousel_car') ){
		function limoking_get_modern_carousel_car($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="limoking-car-carousel-item limoking-item" >';		
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="car-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="limoking-item limoking-car-item limoking-modern-car">';
				
				$car_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="car-thumbnail">';
				$ret .= limoking_get_car_feature_image($thumbnail_size, true);
				$ret .= '</div>'; // car-thumbnail
				$ret .= '<h3 class="car-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // limoking-item
			
			return $ret;
		}		
	}
	
?>