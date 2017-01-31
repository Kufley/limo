<?php
	/*	
	*	Goodlayers Car Contact Form File
	*/

	add_filter( 'wpcf7_form_tag', 'limoking_wpcf7_select_vehicle', 10, 2);  
	if( !function_exists('limoking_wpcf7_select_vehicle') ){
		function limoking_wpcf7_select_vehicle ( $tag, $unused ) {  
			global $limoking_contact_car, $limoking_trip_type;
			
			if( empty($limoking_contact_car) ){
				$limoking_contact_car = new WP_Query(array(
					'post_type' => 'car',
                    'numberposts' => 99,  
                    'orderby' => 'title',  
                    'order' => 'ASC'
				));
			}
			
			if( $tag['name'] == 'trip-type' && !empty($limoking_trip_type) ){
				$tag['options'][] = 'default:' . $limoking_trip_type;
			}else if( $tag['name'] == 'vehicle' ){
				if( !empty($limoking_contact_car) ){
					while( $limoking_contact_car->have_posts() ){ $limoking_contact_car->the_post();
						$tag['raw_values'][] = get_the_title();  
						$tag['values'][] = get_the_title();  
						$tag['labels'][] = get_the_title();  
					}
				}
			}
			return $tag;  
		}
	}  	

	if( !function_exists('limoking_get_book_now_id') ){
		function limoking_get_book_now_id(){	
			global $limoking_booknow_id, $theme_option;
			
			if( !empty($theme_option['contact-shortcode']) ){
				$limoking_booknow_id = empty($limoking_booknow_id)? 0: $limoking_booknow_id;
				$limoking_booknow_id = $limoking_booknow_id + 1;
				return 'limoking-contact-' . $limoking_booknow_id;
			}
			return 0;
		}
	}
	
	if( !function_exists('limoking_get_book_now_button') ){
		function limoking_get_book_now_button(){	
			global $limoking_booknow_id, $theme_option;
			
			if( !empty($theme_option['contact-shortcode']) ){
				return '<div class="limoking-car-contact-form" id="limoking-contact-' . $limoking_booknow_id . '">' . 
				do_shortcode($theme_option['contact-shortcode']) .
				'</div>';
			}
			return '';
		}
	}
	
?>