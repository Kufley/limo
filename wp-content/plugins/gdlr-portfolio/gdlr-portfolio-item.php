<?php
	/*	
	*	Goodlayers Portfolio Item Management File
	*	---------------------------------------------------------------------
	*	This file contains functions that help you create portfolio item
	*	---------------------------------------------------------------------
	*/
	
	// add action to check for portfolio item
	add_action('limoking_print_item_selector', 'limoking_check_portfolio_item', 10, 2);
	if( !function_exists('limoking_check_portfolio_item') ){
		function limoking_check_portfolio_item( $type, $settings = array() ){
			if($type == 'portfolio'){
				echo limoking_print_portfolio_item( $settings );
			}
		}
	}

	// include portfolio script
	if( !function_exists('limoking_include_portfolio_scirpt') ){
		function limoking_include_portfolio_scirpt( $settings = array() ){
			wp_enqueue_script('isotope', get_template_directory_uri() . '/plugins/jquery.isotope.min.js', array(), '1.0', true);
			wp_enqueue_script('jquery.transit', get_template_directory_uri() . '/plugins/jquery.transit.min.js', array(), '1.0', true);	
			wp_enqueue_script('portfolio-script', plugins_url('gdlr-portfolio-script.js', __FILE__), array(), '1.0', true);			
		}
	}
	
	// print portfolio item
	if( !function_exists('limoking_print_portfolio_item') ){
		function limoking_print_portfolio_item( $settings = array() ){
			limoking_include_portfolio_scirpt();
		
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-blog-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
			
			if( $settings['portfolio-layout'] == 'carousel' ){ 
				$settings['carousel'] = true;
			}
			
			$ret  = limoking_get_item_title($settings);				
			$ret .= '<div class="portfolio-item-wrapper type-' . $settings['portfolio-style'] . '" ';
			$ret .= $item_id . $margin_style . ' data-ajax="' . AJAX_URL . '" >'; 
			
			// query posts section
			$args = array('post_type' => 'portfolio', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : 1;

			if( !empty($settings['category']) || (!empty($settings['tag']) && $settings['portfolio-filter'] == 'disable') ){
				$args['tax_query'] = array('relation' => 'OR');
				
				if( !empty($settings['category']) ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'portfolio_category', 'field'=>'slug'));
				}
				if( !empty($settings['tag']) && $settings['portfolio-filter'] == 'disable' ){
					array_push($args['tax_query'], array('terms'=>explode(',', $settings['tag']), 'taxonomy'=>'portfolio_tag', 'field'=>'slug'));
				}				
			}			
			$query = new WP_Query( $args );

			// create the portfolio filter
			$settings['num-excerpt'] = empty($settings['num-excerpt'])? 0: $settings['num-excerpt'];
			$settings['portfolio-size'] = str_replace('1/', '', $settings['portfolio-size']);
			$settings['thumbnail-size-featured'] = empty($settings['thumbnail-size-featured'])? $settings['thumbnail-size']: $settings['thumbnail-size-featured'];
			if( $settings['portfolio-filter'] == 'enable' ){
			
				// ajax infomation
				$ret .= '<div class="limoking-ajax-info" data-num-fetch="' . $args['posts_per_page'] . '" data-num-excerpt="' . $settings['num-excerpt'] . '" ';
				$ret .= 'data-orderby="' . $args['orderby'] . '" data-order="' . $args['order'] . '" data-thumbnail-size-featured="' . $settings['thumbnail-size-featured'] . '" ';
				$ret .= 'data-thumbnail-size="' .  $settings['thumbnail-size'] . '" data-port-style="' . $settings['portfolio-style'] . '" ';
				$ret .= 'data-port-size="' . $settings['portfolio-size'] . '" data-port-layout="' .  $settings['portfolio-layout'] . '" ';
				$ret .= 'data-ajax="' . admin_url('admin-ajax.php') . '" data-category="' . $settings['category'] . '" data-pagination="' . $settings['pagination'] . '" ></div>';
			
				// category filter
				if( empty($settings['category']) ){
					$parent = array('limoking-all'=>__('All', 'gdlr-portfolio'));
					$settings['category-id'] = '';
				}else{
					$term = get_term_by('slug', $settings['category'], 'portfolio_category');
					$parent = array($settings['category']=>$term->name);
					$settings['category-id'] = $term->term_id;
				}
				
				$filters = $parent + limoking_get_term_list('portfolio_category', $settings['category-id']);
				$filter_active = 'active';
				$ret .= '<div class="portfolio-item-filter">';
				foreach($filters as $filter_id => $filter){
					$filter_id = ($filter_id == 'limoking-all')? '': $filter_id;
					$ret .= '<span class="limoking-separator" >/</span>';
					$ret .= '<a class="' . $filter_active . '" href="#" ';
					$ret .= 'data-category="' . $filter_id . '" >' . $filter . '</a>';
					$filter_active = '';
				}
				$ret .= '</div>';
			}
			
			$no_space  = (strpos($settings['portfolio-style'], 'no-space') > 0)? 'limoking-item-no-space': '';
			$no_space .= ' limoking-portfolio-column-' . $settings['portfolio-size'];
			$ret .= '<div class="portfolio-item-holder ' . $no_space . '">';
			if( $settings['portfolio-style'] == 'classic-portfolio' || 
				$settings['portfolio-style'] == 'classic-portfolio-no-space'){
				
				global $limoking_excerpt_length; $limoking_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'limoking_set_excerpt_length');
				
				$ret .= limoking_get_classic_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'] );
							
				remove_filter('excerpt_length', 'limoking_set_excerpt_length');
			}else if($settings['portfolio-style'] == 'modern-portfolio' || 
				$settings['portfolio-style'] == 'modern-portfolio-no-space'){	
				
				$ret .= limoking_get_modern_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'], $settings['thumbnail-size-featured'] );
			}
			$ret .= '<div class="clear"></div>';
			$ret .= '</div>';
			
			// create pagination
			if($settings['portfolio-filter'] == 'enable' && $settings['pagination'] == 'enable'){
				$ret .= limoking_get_ajax_pagination($query->max_num_pages, $args['paged']);
			}else if($settings['pagination'] == 'enable'){
				$ret .= limoking_get_pagination($query->max_num_pages, $args['paged']);
			}
			
			$ret .= '</div>'; // portfolio-item-wrapper
			return $ret;
		}
	}
	
	// ajax function for portfolio filter / pagination
	add_action('wp_ajax_limoking_get_portfolio_ajax', 'limoking_get_portfolio_ajax');
	add_action('wp_ajax_nopriv_limoking_get_portfolio_ajax', 'limoking_get_portfolio_ajax');
	if( !function_exists('limoking_get_portfolio_ajax') ){
		function limoking_get_portfolio_ajax(){
			$settings = $_POST['args'];

			$args = array('post_type' => 'portfolio', 'suppress_filters' => false);
			$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
			$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
			$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
			$args['paged'] = (empty($settings['paged']))? 1: $settings['paged'];
				
			if( !empty($settings['category']) ){
				$args['tax_query'] = array(
					array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'portfolio_category', 'field'=>'slug')
				);
			}			
			$query = new WP_Query( $args );
			
			$no_space = (strpos($settings['portfolio-style'], 'no-space') > 0)? 'limoking-item-no-space': '';
			$no_space .= ' limoking-portfolio-column-' . $settings['portfolio-size'];
			$ret  = '<div class="portfolio-item-holder ' . $no_space . '">';
			if( $settings['portfolio-style'] == 'classic-portfolio' || 
				$settings['portfolio-style'] == 'classic-portfolio-no-space'){
				
				global $limoking_excerpt_length; $limoking_excerpt_length = $settings['num-excerpt'];
				add_filter('excerpt_length', 'limoking_set_excerpt_length');
				
				$ret .= limoking_get_classic_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'] );
							
				remove_filter('excerpt_length', 'limoking_set_excerpt_length');
			}else if($settings['portfolio-style'] == 'modern-portfolio' || 
				$settings['portfolio-style'] == 'modern-portfolio-no-space'){	
				
				$ret .= limoking_get_modern_portfolio($query, $settings['portfolio-size'], 
							$settings['thumbnail-size'], $settings['portfolio-layout'], $settings['thumbnail-size-featured'] );
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
	
	// get portfolio info
	if( !function_exists('limoking_get_portfolio_info') ){
		function limoking_get_portfolio_info( $array = array(), $option = array(), $wrapper = true ){
			$ret = '';
			
			foreach($array as $post_info){	
				switch( $post_info ){
					case 'clients':
						if(empty($option['clients'])) break;
					
						$ret .= '<div class="portfolio-info portfolio-clients">';
						$ret .= '<span class="info-head limoking-title">' . __('Client', 'gdlr-portfolio') . ' </span>';
						$ret .= $option['clients'];						
						$ret .= '</div>';						
					
						break;	
					case 'skills':
						if(empty($option['skills'])) break;
					
						$ret .= '<div class="portfolio-info portfolio-skills">';
						$ret .= '<span class="info-head limoking-title">' . __('Skills', 'gdlr-portfolio') . ' </span>';
						$ret .= $option['skills'];						
						$ret .= '</div>';						

						break;	
					case 'website':
						if(empty($option['website'])) break;
					
						$ret .= '<div class="portfolio-info portfolio-website">';
						$ret .= '<span class="info-head limoking-title">' . __('Website', 'gdlr-portfolio') . ' </span>';
						$ret .= '<a href="' . $option['website'] . '" target="_blank" >' . $option['website'] . '</a>';					
						$ret .= '</div>';						
					
						break;
					case 'tag':
						$tag = get_the_term_list(get_the_ID(), 'portfolio_tag', '', '<span class="sep">,</span> ' , '' );
						if(empty($tag)) break;					
					
						$ret .= '<div class="portfolio-info portfolio-tag">';
						$ret .= '<span class="info-head limoking-title">' . __('Tags', 'gdlr-portfolio') . ' </span>';
						$ret .= $tag;						
						$ret .= '</div>';						
						break;					
				}
			}

			if($wrapper && !empty($ret)){
				return '<div class="limoking-portfolio-info limoking-info-font">' . $ret . '<div class="clear"></div></div>';
			}else if( !empty($ret) ){
				return $ret . '<div class="clear"></div>';
			}
			return '';
		}
	}

	// get portfolio thumbnail class
	if( !function_exists('limoking_get_portfolio_thumbnail_class') ){
		function limoking_get_portfolio_thumbnail_class( $post_option ){
			global $limoking_related_section;
			if( is_single() && $post_option['inside-thumbnail-type'] != 'thumbnail-type'
				&& empty($limoking_related_section) ){ $type = 'inside-';
			}else{ $type = ''; }	

			switch($post_option[$type . 'thumbnail-type']){
				case 'feature-image': return 'limoking-image' ;
				case 'image': return 'limoking-image' ;
				case 'video': return 'limoking-video' ;
				case 'slider': return 'limoking-slider' ;		
				case 'stack-images': return 'limoking-stack-images' ;
				default: return '';
			}			
		}
	}

	// get portfolio icon class
	if( !function_exists('limoking_get_portfolio_icon_class') ){
		function limoking_get_portfolio_icon_class($post_option){
			global $theme_option;
		
			switch($post_option['thumbnail-link']){
				case 'current-post': return 'fa fa-link' ;
				case 'current': return 'fa fa-search' ;
				case 'url': return 'fa fa-link' ;
				case 'image': return 'fa fa-search' ;
				case 'video': return 'fa fa-film' ;
				default: return 'fa fa-link';
			}			
		}
	}	
	
	// get portfolio link attribute
	if( !function_exists('limoking_get_portfolio_thumbnail_link') ){
		function limoking_get_portfolio_thumbnail_link($post_option, $location = 'media'){
			if($location == 'title'){  
				$link_type = (!empty($post_option['thumbnail-link']) && $post_option['thumbnail-link'] == 'url')? 'url': 'current-post';
			}else{
				$link_type = $post_option['thumbnail-link'];
			}
		
			switch($link_type){
				case 'current':
					$image_full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
					return ' href="' . $image_full[0] . '" data-rel="fancybox" ';
				case 'url': 
					$ret  = ' href="' . $post_option['thumbnail-url'] . '" ';
					$ret .= ($post_option['thumbnail-new-tab'] == 'enable')? 'target="_blank" ': '';
					return $ret;
				case 'image': return ' href="' . $post_option['thumbnail-url'] . '" data-rel="fancybox" ';
				case 'video': return ' href="' . $post_option['thumbnail-url'] . '" data-rel="fancybox" data-fancybox-type="iframe" ';
				case 'current-post': default: return ' href="' . get_permalink() . '" ';
			}
			
		}
	}	
	
	// get portfolio thumbnail
	if( !function_exists('limoking_get_portfolio_thumbnail') ){
		function limoking_get_portfolio_thumbnail($post_option, $size = 'full', $modern_style = false){
			global $limoking_related_section;
			if( is_single() && $post_option['inside-thumbnail-type'] != 'thumbnail-type'
				&& empty($limoking_related_section)){ $type = 'inside-';
			}else{ $type = ''; }
			
			$ret = '';
			switch($post_option[$type . 'thumbnail-type']){
				case 'feature-image':
					$image_id = get_post_thumbnail_id();
					if( !empty($image_id) ){
						if( !is_single() || $limoking_related_section ){
							$ret  = limoking_get_image($image_id, $size);
							$ret .= '<span class="portfolio-overlay" >&nbsp;</span>';
							$ret .= '<a class="portfolio-overlay-icon" ' . limoking_get_portfolio_thumbnail_link($post_option) . ' >';
							$ret .= '<span class="portfolio-icon" ><i class="' . limoking_get_portfolio_icon_class($post_option) . '" ></i></span>';
							$ret .= '</a>';			
						}else{
							$ret  = limoking_get_image($image_id, $size, true);
						}
					}
					break;			
				case 'image':
					$ret = limoking_get_image($post_option[$type . 'thumbnail-image'], $size, true);
					break;
				case 'video': 
					if( is_single() && empty($limoking_related_section) ){
						$ret = limoking_get_video($post_option[$type . 'thumbnail-video'], 'full');
					}else{
						$ret = limoking_get_video($post_option[$type . 'thumbnail-video'], $size);
					}
					break;
				case 'slider': 
					$ret = limoking_get_slider($post_option[$type . 'thumbnail-slider'], $size);
					break;					
				case 'stack-image': 
					$ret = limoking_get_stack_images($post_option[$type . 'thumbnail-slider']);
					break;
				default :
					$ret = '';
			}			

			return $ret;
		}
	}	
	
	// print classic portfolio
	if( !function_exists('limoking_get_classic_portfolio') ){
		function limoking_get_classic_portfolio($query, $size, $thumbnail_size, $layout = 'fitRows'){
			if($layout == 'carousel'){ 
				return limoking_get_classic_carousel_portfolio($query, $size, $thumbnail_size); 
			}		
		
			global $post;

			$current_size = 0;
			$ret  = '<div class="limoking-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}			
    
				$ret .= '<div class="' . limoking_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="limoking-item limoking-portfolio-item limoking-classic-portfolio">';
				$ret .= '<div class="limoking-ux limoking-classic-portfolio-ux">';
				
				$port_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . limoking_get_portfolio_thumbnail_class($port_option) . '">';
				$ret .= limoking_get_portfolio_thumbnail($port_option, $thumbnail_size);
				$ret .= '</div>'; // portfolio-thumbnail
				
				$ret .= '<div class="portfolio-classic-content">';
				$ret .= '<h3 class="portfolio-title"><a ' . limoking_get_portfolio_thumbnail_link($port_option, 'title') . ' >' . get_the_title() . '</a></h3>';
				$ret .= limoking_get_portfolio_info(array('tag'));
				$ret .= '<div class="portfolio-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>'; // portfolio-classic-content
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
	if( !function_exists('limoking_get_classic_carousel_portfolio') ){
		function limoking_get_classic_carousel_portfolio($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="limoking-portfolio-carousel-item limoking-item" >';	
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="portfolio-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="limoking-item limoking-portfolio-item limoking-classic-portfolio">';

				$port_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . limoking_get_portfolio_thumbnail_class($port_option) . '">';
				$ret .= limoking_get_portfolio_thumbnail($port_option, $thumbnail_size);
				$ret .= '</div>'; // portfolio-thumbnail
				
				$ret .= '<div class="portfolio-classic-content">';
				$ret .= limoking_get_portfolio_info(array('tag'));
				$ret .= '<div class="portfolio-excerpt">' . get_the_excerpt() . '</div>';
				$ret .= '</div>';
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>';
			$ret .= '</div>';
			
			return $ret;
		}		
	}	
	
	// print modern portfolio
	if( !function_exists('limoking_get_modern_portfolio') ){
		function limoking_get_modern_portfolio($query, $size, $thumbnail_size, $layout = 'fitRows', $thumbnail_size_featured = 'full'){
			if($layout == 'carousel'){ 
				return limoking_get_modern_carousel_portfolio($query, $size, $thumbnail_size); 
			}else if($layout == 'masonry-style-1'){
				$layout = 'masonry';
				$featured_post = array(0);
			}else if($layout == 'masonry-style-2'){
				$layout = 'masonry';
				$featured_post = array(0,4,6,7,11,15,17,18);
			}
			
			global $post;

			$current_size = 0;
			$ret  = '<div class="limoking-isotope" data-type="portfolio" data-layout="' . $layout  . '" >';
			while($query->have_posts()){ $query->the_post();
				
				if( $current_size % $size == 0 ){
					$ret .= '<div class="clear"></div>';
				}	
    
				$ret .= '<div class="' . limoking_get_column_class('1/' . $size) . '">';
				$ret .= '<div class="limoking-item limoking-portfolio-item limoking-modern-portfolio">';
				$ret .= '<div class="limoking-ux limoking-modern-portfolio-ux">';
				
				$port_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . limoking_get_portfolio_thumbnail_class($port_option) . '">';
				if( !empty($featured_post) && in_array($current_size, $featured_post) ){
					$ret .= limoking_get_portfolio_thumbnail($port_option, $thumbnail_size_featured, true);
				}else{
					$ret .= limoking_get_portfolio_thumbnail($port_option, $thumbnail_size, true);
				}
				$ret .= '</div>'; // portfolio-thumbnail	
				
				$ret .= '<h3 class="portfolio-title"><a ' . limoking_get_portfolio_thumbnail_link($port_option, 'title') . ' >' . get_the_title() . '</a></h3>';
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
	if( !function_exists('limoking_get_modern_carousel_portfolio') ){
		function limoking_get_modern_carousel_portfolio($query, $size, $thumbnail_size){	
			global $post;

			$ret  = '<div class="limoking-portfolio-carousel-item limoking-item" >';		
			$ret .= '<div class="flexslider" data-type="carousel" data-nav-container="portfolio-item-wrapper" data-columns="' . $size . '" >';	
			$ret .= '<ul class="slides" >';
			while($query->have_posts()){ $query->the_post();
				$ret .= '<li class="limoking-item limoking-portfolio-item limoking-modern-portfolio">';
				
				$port_option = json_decode(limoking_decode_preventslashes(get_post_meta($post->ID, 'post-option', true)), true);
				$ret .= '<div class="portfolio-thumbnail ' . limoking_get_portfolio_thumbnail_class($port_option) . '">';
				$ret .= limoking_get_portfolio_thumbnail($port_option, $thumbnail_size, true);
				$ret .= '</div>'; // portfolio-thumbnail
				$ret .= '<h3 class="portfolio-title"><a ' . limoking_get_portfolio_thumbnail_link($port_option, 'title') . ' >' . get_the_title() . '</a></h3>';
				$ret .= '</li>';
			}			
			$ret .= '</ul>';
			$ret .= '</div>'; // flexslider
			$ret .= '</div>'; // limoking-item
			
			return $ret;
		}		
	}
	
?>