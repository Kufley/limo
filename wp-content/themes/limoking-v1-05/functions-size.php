<?php
	/*	
	*	Goodlayers Size Registered Function
	*	---------------------------------------------------------------------
	*	This file contains the script to register the thumbnail size used in 
	*	the theme, you can add / remove your own size here.
	*	
	*	reference : 
	*	crop mode difference :
	*	---------------------------------------------------------------------
	*/

	$limoking_thumbnail_size = array(
		'post-thumbnail-size' => array('width'=>750, 'height'=>330, 'crop'=>true),
		'round-personnel-size' => array('width'=>400, 'height'=>400, 'crop'=>true),
		'small-grid-size' => array('width'=>400, 'height'=>300, 'crop'=>true),
		'portrait' => array('width'=>440, 'height'=>550, 'crop'=>true),
		'post-slider-side' => array('width'=>750, 'height'=>330, 'crop'=>true),
		'full-slider' => array('width'=>980, 'height'=>380, 'crop'=>true),
		'portfolio-portrait' => array('width'=>500, 'height'=>550, 'crop'=>true),
		'blog-grid' => array('width'=>700, 'height'=>400, 'crop'=>true),
		'portfolio-featured' => array('width'=>540, 'height'=>697, 'crop'=>true),
		'portfolio-half' => array('width'=>540, 'height'=>326, 'crop'=>true),
		
		//add_image_size( 'category-thumb', 300, 9999 ); //300 pixels wide (and unlimited height)
	);
	$limoking_thumbnail_size = apply_filters('limoking-thumbnail-size', $limoking_thumbnail_size);
	
	// create the size from limoking_thumbnail_size variable
	add_action( 'after_setup_theme', 'limoking_register_thumbnail_size' );
	if( !function_exists('limoking_register_thumbnail_size') ){
		function limoking_register_thumbnail_size(){
			add_theme_support( 'post-thumbnails' );
		
			global $limoking_thumbnail_size;		
			foreach($limoking_thumbnail_size as $limoking_size_slug => $limoking_size){
				add_image_size($limoking_size_slug, $limoking_size['width'], $limoking_size['height'], $limoking_size['crop']);
			}
		}
	}
	
	// add the image size filter to admin option
	add_filter('image_size_names_choose', 'limoking_set_custom_size_image');
	if( !function_exists('limoking_set_custom_size_image') ){
		function limoking_set_custom_size_image( $sizes ){	
			$additional_size = array();
			
			global $limoking_thumbnail_size;
			foreach($limoking_thumbnail_size as $limoking_size_slug => $limoking_size){
				$additional_size[$limoking_size_slug] = $limoking_size_slug;
			}
			
			return array_merge($sizes, $additional_size);
		}
	}		
	
	// get all available image sizes
	if( !function_exists('limoking_get_thumbnail_list') ){
		function limoking_get_thumbnail_list(){
			global $limoking_thumbnail_size, $_wp_additional_image_sizes;
			
			$sizes = array();
			foreach( get_intermediate_image_sizes() as $size ){
				if(in_array( $size, array( 'thumbnail', 'medium', 'large' )) ){
					$sizes[$size] = $size . ' -- ' . get_option($size . '_size_w') . 'x' . get_option($size . '_size_h');
				}else if( !empty($limoking_thumbnail_size[$size]) ){
					$sizes[$size] = $size . ' -- ' . $limoking_thumbnail_size[$size]['width'] . 'x' . $limoking_thumbnail_size[$size]['height'];
				}
				//else{
				//	if( isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$s]) ){
				//		$sizes[$size] = $size . ' -- ' . $_wp_additional_image_sizes[$size]['width'] . 'x' . $_wp_additional_image_sizes[$size]['height'];
				//	}
				//}
			}
			$sizes['full'] = esc_html__('full size (Original Images)', 'limoking');
			
			return $sizes;
		}	
	}
	
	// video size 
	if( !function_exists('limoking_get_video_size') ){
		function limoking_get_video_size( $size ){
			global $_wp_additional_image_sizes, $theme_option, $limoking_crop_video;

			// get video ratio
			if( !empty($theme_option['video-ratio']) && 
				preg_match('#^(\d+)[\/:](\d+)$#', $theme_option['video-ratio'], $number)){
				$ratio = $number[1]/$number[2];
			}else{
				$ratio = 16/9;
			}
			
			// get video size
			$video_size = array('width'=>620, 'height'=>9999);
			if( !empty($size) && is_numeric($size) ){
				$video_size['width'] = intval($size);
			}else if( !empty($size) && !empty($_wp_additional_image_sizes[$size]) ){
				$video_size = $_wp_additional_image_sizes[$size];
			}else if( !empty($size) && in_array($size, get_intermediate_image_sizes()) ){
				$video_size = array('width'=>get_option($size . '_size_w'), 'height'=>get_option($size . '_size_h'));
			}

			// refine video size
			if( $limoking_crop_video || $video_size['height'] == 9999 ){
				return array('width'=>$video_size['width'], 'height'=>intval($video_size['width'] / $ratio));
			}else if( $video_size['width'] == 9999 ){
				return array('width'=>intval($video_size['height'] * $ratio), 'height'=>$video_size['height']);
			}
			return $video_size;
		}	
	}	
?>