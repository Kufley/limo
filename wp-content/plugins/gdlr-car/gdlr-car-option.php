<?php
	/*	
	*	Goodlayers Car Option file
	*	---------------------------------------------------------------------
	*	This file creates all car options and attached to the theme
	*	---------------------------------------------------------------------
	*/
	
	// add a car option to car page
	if( is_admin() ){ add_action('after_setup_theme', 'limoking_create_car_options'); }
	if( !function_exists('limoking_create_car_options') ){
	
		function limoking_create_car_options(){
			global $limoking_sidebar_controller;
			
			if( !class_exists('limoking_page_options') ) return;
			new limoking_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('car'),
					'meta_title' => __('Goodlayers Car Option', 'gdlr-car'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => __('Page Layout', 'gdlr-car'),
						'options' => array(
							'header-background' => array(
								'title' => __('Header Background Image' , 'gdlr-car'),
								'button' => __('Upload', 'gdlr-car'),
								'type' => 'upload',
							),
							'page-caption' => array(
								'title' => __('Page Caption' , 'gdlr-car'),
								'type' => 'textarea'
							),					
						)
					),
					
					'page-option' => array(
						'title' => __('Page Option', 'gdlr-car'),
						'options' => array(
							'per-hour-rate-amount' => array(
								'title' => __('Per Hour Rate Amount' , 'gdlr-car'),
								'type' => 'text'
							),
							'per-hour-rate-caption' => array(
								'title' => __('Per Hour Rate Caption' , 'gdlr-car'),
								'type' => 'text'
							),
							'per-day-rate-amount' => array(
								'title' => __('Per Day Rate Amount' , 'gdlr-car'),
								'type' => 'text'
							),
							'per-day-rate-caption' => array(
								'title' => __('Per Day Rate Caption' , 'gdlr-car'),
								'type' => 'text'
							),
							'airport-transfer-amount' => array(
								'title' => __('Airport Transfer Amount' , 'gdlr-car'),
								'type' => 'text'
							),
							'airport-transfer-caption' => array(
								'title' => __('Airport Transfer Caption' , 'gdlr-car'),
								'type' => 'text'
							),
							'car-info' => array(
								'title' => __('Car Info' , 'gdlr-car'),
								'type' => 'carinfo',
								'wrapper-class' => 'limoking-carinfo-option-wrapper limoking-top-divider'
							),
							
							'car-info-thumbnail' => array(
								'title' => __('Single Car Info Thumbnail' , 'gdlr-car'),
								'type' => 'upload',
								'wrapper-class' => 'limoking-top-divider'
							),						
							'inside-thumbnail-type' => array(
								'title' => __('Single Car Thumbnail Type' , 'gdlr-car'),
								'type' => 'combobox',
								'options' => array(
									'feature-image'=> __('Feature Image', 'gdlr-car'),
									'image'=> __('Image', 'gdlr-car'),
									'video'=> __('Video', 'gdlr-car'),
									'slider'=> __('Slider', 'gdlr-car'),
									'stack-image'=> __('Stack Images', 'gdlr-car')
								),
								'wrapper-class' => 'limoking-top-divider'
							),		
							'inside-thumbnail-image' => array(
								'title' => __('Image Url' , 'gdlr-car'),
								'type' => 'upload',
								'wrapper-class' => 'image-wrapper inside-thumbnail-type-wrapper'
							),							
							'inside-thumbnail-video' => array(
								'title' => __('Video Url' , 'gdlr-car'),
								'type' => 'text',
								'wrapper-class' => 'video-wrapper inside-thumbnail-type-wrapper'
							),		
							'inside-thumbnail-slider' => array(
								'title' => __('Slider' , 'gdlr-car'),
								'type' => 'slider',
								'wrapper-class' => 'stack-image-wrapper slider-wrapper inside-thumbnail-type-wrapper'
							),								
						)
					),

				)
			);
			
		}
	}	
	
	// add car in page builder area
	add_filter('limoking_page_builder_option', 'limoking_register_car_item');
	if( !function_exists('limoking_register_car_item') ){
		function limoking_register_car_item( $page_builder = array() ){
			global $limoking_spaces;
		
			$page_builder['content-item']['options']['car'] = array(
				'title'=> __('Car', 'gdlr-car'), 
				'type'=>'item',
				'options'=>array_merge(limoking_page_builder_title_option(true), array(					
					'category'=> array(
						'title'=> __('Category' ,'gdlr-car'),
						'type'=> 'multi-combobox',
						'options'=> limoking_get_term_list('car_category'),
						'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-car')
					),	
					'tag'=> array(
						'title'=> __('Tag' ,'gdlr-car'),
						'type'=> 'multi-combobox',
						'options'=> limoking_get_term_list('car_tag'),
						'description'=> __('Will be ignored when the car filter option is enabled.', 'gdlr-car')
					),					
					'car-style'=> array(
						'title'=> __('Car Style' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'classic-car' => __('Classic Style', 'gdlr-car'),
							'modern-car' => __('Modern Style', 'gdlr-car'),
						),
					),					
					'car-price-display'=> array(
						'title'=> __('Car Price Display' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'none' => __('None', 'gdlr-car'),
							'per-hour-rate-amount' => __('Hour Rate', 'gdlr-car'),
							'per-day-rate-amount' => __('Day Rate', 'gdlr-car'),
							'airport-transfer-amount' => __('Airport Transfer', 'gdlr-car'),
						),
						'wrapper-class' => 'car-style-wrapper classic-car-wrapper'
					),					
					'num-fetch'=> array(
						'title'=> __('Num Fetch' ,'gdlr-car'),
						'type'=> 'text',	
						'default'=> '8',
						'description'=> __('Specify the number of cars you want to pull out.', 'gdlr-car')
					),				
					'car-size'=> array(
						'title'=> __('Car Size' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'1/4'=>'1/4',
							'1/3'=>'1/3',
							'1/2'=>'1/2',
							'1/1'=>'1/1'
						),
						'default'=>'1/3'
					),					
					'car-layout'=> array(
						'title'=> __('Car Layout Order' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'fitRows' =>  __('FitRows ( Order items by row )', 'gdlr-car'),
							'masonry' => __('Masonry ( Order items by spaces )', 'gdlr-car'),
							'masonry-style-1' => __('Masonry Style 1 ( Only Modern Car )', 'gdlr-car'),
							'masonry-style-2' => __('Masonry Style 2 ( Only Modern Car )', 'gdlr-car'),
							'carousel' => __('Carousel ( Only For Grid And Modern Style )', 'gdlr-car'),
						),
						'description'=> __('You can see an example of these two layout here', 'gdlr-car') . 
							'<br><br> http://isotope.metafizzy.co/demos/layout-modes.html'
					),
					'car-filter'=> array(
						'title'=> __('Enable Car filter' ,'gdlr-car'),
						'type'=> 'checkbox',
						'default'=> 'disable',
						'description'=> __('*** You have to select only 1 ( or none ) car category when enable this option. This option cannot works with carousel function.','gdlr-car')
					),						
					'thumbnail-size'=> array(
						'title'=> __('Thumbnail Size' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> limoking_get_thumbnail_list(),
						'description'=> __('Only effects to <strong>standard and gallery post format</strong>','gdlr-car')
					),						
					'thumbnail-size-featured'=> array(
						'title'=> __('Featured Thumbnail Size' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> limoking_get_thumbnail_list(),
						'wrapper-class'=> 'car-layout-wrapper masonry-style-1-wrapper masonry-style-2-wrapper',
						'description'=> __('For "Masonry" layout','gdlr-car')
					),	
					'orderby'=> array(
						'title'=> __('Order By' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'date' => __('Publish Date', 'gdlr-car'), 
							'title' => __('Title', 'gdlr-car'), 
							'rand' => __('Random', 'gdlr-car'), 
						)
					),
					'order'=> array(
						'title'=> __('Order' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'desc'=>__('Descending Order', 'gdlr-car'), 
							'asc'=> __('Ascending Order', 'gdlr-car'), 
						)
					),			
					'pagination'=> array(
						'title'=> __('Enable Pagination' ,'gdlr-car'),
						'type'=> 'checkbox'
					),					
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr-car'),
						'type' => 'text',
						'default' => $limoking_spaces['bottom-blog-item'],
						'description' => __('Spaces after ending of this item', 'gdlr-car')
					),				
				))
			);
			$page_builder['content-item']['options']['car-rate'] = array(
				'title'=> __('Car Rate Table', 'gdlr-car'), 
				'type'=>'item',
				'options'=>array(					
					'category'=> array(
						'title'=> __('Category' ,'gdlr-car'),
						'type'=> 'multi-combobox',
						'options'=> limoking_get_term_list('car_category'),
						'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-car')
					),					
					'feature-rate'=> array(
						'title'=> __('Feature Rate' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'hour-rate' => __('Hour Rate', 'gdlr-car'),
							'day-rate' => __('Day Rate', 'gdlr-car'),
							'airport-transfer' => __('Airport Transfer', 'gdlr-car'),
						),
						'wrapper-class' => 'car-style-wrapper classic-car-wrapper'
					),					
					'num-fetch'=> array(
						'title'=> __('Num Fetch' ,'gdlr-car'),
						'type'=> 'text',	
						'default'=> '8',
						'description'=> __('Specify the number of cars you want to pull out.', 'gdlr-car')
					),			
					'thumbnail-size'=> array(
						'title'=> __('Thumbnail Size' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> limoking_get_thumbnail_list(),
						'description'=> __('Only effects to <strong>standard and gallery post format</strong>','gdlr-car')
					),	
					'orderby'=> array(
						'title'=> __('Order By' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'date' => __('Publish Date', 'gdlr-car'), 
							'title' => __('Title', 'gdlr-car'), 
							'rand' => __('Random', 'gdlr-car'), 
						)
					),
					'order'=> array(
						'title'=> __('Order' ,'gdlr-car'),
						'type'=> 'combobox',
						'options'=> array(
							'desc'=>__('Descending Order', 'gdlr-car'), 
							'asc'=> __('Ascending Order', 'gdlr-car'), 
						)
					),				
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr-car'),
						'type' => 'text',
						'default' => $limoking_spaces['bottom-blog-item'],
						'description' => __('Spaces after ending of this item', 'gdlr-car')
					),				
				)
			);			
			return $page_builder;
		}
	}
	
?>