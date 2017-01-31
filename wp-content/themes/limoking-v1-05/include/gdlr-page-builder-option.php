<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the admin option setting 
	*	---------------------------------------------------------------------
	*/
	
	// add ability to search through page builder
	add_filter( 'posts_where', 'limoking_search_page_builder_meta');
	if( !function_exists('limoking_search_page_builder_meta') ){
		function limoking_search_page_builder_meta( $where ) {
			if( is_search() && empty($_GET['post_type']) && !is_admin() ) {
				global $wpdb;
				$query = get_search_query();
				$query = $wpdb->esc_like( $query );

				$where .= " OR {$wpdb->posts}.ID IN (";
				$where .= "SELECT {$wpdb->postmeta}.post_id ";
				$where .= "FROM {$wpdb->posts}, {$wpdb->postmeta} ";
				$where .= "WHERE {$wpdb->posts}.post_type = 'page' ";
				$where .= "AND {$wpdb->posts}.post_status = 'publish' ";
				$where .= "AND {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ";
				$where .= "AND {$wpdb->postmeta}.meta_key IN('above-sidebar', 'content-with-sidebar', 'below-sidebar') ";
				$where .= "AND {$wpdb->postmeta}.meta_value LIKE '%$query%' )";
			}
			return $where;
		}
	}
	
	// returns array of options for title of each item
	if( !function_exists('limoking_page_builder_title_option') ){
		function limoking_page_builder_title_option( $read_more = false ) {
			$title = array(
				'title-type'=> array(	
					'title'=> esc_html__('Title Type' ,'limoking'),
					'type'=> 'combobox',
					'options'=> array(
						'none'=> esc_html__('None' ,'limoking'),
						'center'=> esc_html__('Center Align' ,'limoking'),
						'center-divider'=> esc_html__('Center Align with Divider' ,'limoking'),
						'center-icon-divider'=> esc_html__('Center Align with Icon Divider' ,'limoking'),
						'left'=> esc_html__('Left Align' ,'limoking'),
						'left-divider'=> esc_html__('Left Align with Divider' ,'limoking'),
					)
				),
				'title-size'=> array(	
					'title'=> esc_html__('Title Size' ,'limoking'),
					'type'=> 'combobox',
					'options'=> array(
						'small'=> esc_html__('Small' ,'limoking'),
						'medium'=> esc_html__('Medium' ,'limoking'),
						'large'=> esc_html__('Large' ,'limoking'),
						'extra-large'=> esc_html__('Extra Large' ,'limoking'),
					),
					'default'=>'medium',
					'wrapper-class'=>'title-type-wrapper left-wrapper left-divider-wrapper center-wrapper center-divider-wrapper center-icon-divider-wrapper'
				),										
				'title'=> array(	
					'title'=> esc_html__('Title' ,'limoking'),
					'type'=> 'text',
					'wrapper-class'=>'title-type-wrapper left-wrapper left-divider-wrapper center-wrapper center-divider-wrapper center-icon-divider-wrapper'
				),			
				'caption'=> array(	
					'title'=> esc_html__('Caption' ,'limoking'),
					'type'=> 'textarea',
					'wrapper-class'=>'title-type-wrapper left-wrapper left-divider-wrapper center-wrapper center-divider-wrapper center-icon-divider-wrapper'
				),			
				'title-icon-class'=> array(	
					'title'=> esc_html__('Icon Class' ,'limoking'),
					'type'=> 'text',
					'wrapper-class'=>'title-type-wrapper center-icon-divider-wrapper'
				)			
			);
			
			if( $read_more ){
				$title['right-text'] = array(	
					'title'=> esc_html__('Titlte Link Text' ,'limoking'),
					'type'=> 'text',
					'default'=> esc_html__('Read All News', 'limoking'),
					'wrapper-class'=>'title-type-wrapper left-wrapper left-divider-wrapper center-wrapper center-divider-wrapper center-icon-divider-wrapper'
				);	
				$title['right-text-link'] = array(	
					'title'=> esc_html__('Title Link URL' ,'limoking'),
					'type'=> 'text',
					'wrapper-class'=>'title-type-wrapper left-wrapper left-divider-wrapper center-wrapper center-divider-wrapper center-icon-divider-wrapper'
				);			
			}
			
			return $title;
		}
	}

	// create the page builder
	if( is_admin() ){ add_action('init', 'limoking_create_page_builder_option'); }
	if( !function_exists('limoking_create_page_builder_option') ){
	
		function limoking_create_page_builder_option(){
			global $limoking_spaces;
		
			new limoking_page_builder( 
				
				// page builder option attribute
				array(
					'post_type' => array('page'),
					'meta_title' => esc_html__('Page Builder Options', 'limoking'),
				),
					  
				// page builder option setting
				apply_filters('limoking_page_builder_option',
					array(
						'column-wrapper-item' => array(
							'title' => esc_html__('Column Wrapper Item', 'limoking'),
							'blank_option' => esc_html__('- Select Column Item -', 'limoking'),
							'options' => array(
								'column1-5' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'1/5'), 
								'column1-4' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'1/4'), 
								'column2-5' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'2/5'), 
								'column1-3' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'1/3'), 
								'column1-2' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'1/2'), 
								'column3-5' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'3/5'), 
								'column2-3' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'2/3'), 
								'column3-4' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'3/4'), 
								'column4-5' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'4/5'), 
								'column1-1' => array('title'=> esc_html__('Column Item', 'limoking'), 'type'=>'wrapper', 'size'=>'1/1'),
								
								'color-wrapper' => array(
									'title'=> esc_html__('Color Wrapper', 'limoking'), 
									'type'=>'wrapper',
									'options'=>array(
										'background-type' => array(
											'title' => esc_html__('Background Type', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'color'=>esc_html__('Color', 'limoking'),
												'transparent'=>esc_html__('Transparent', 'limoking'),
											)
										),
										'background' => array(
											'title' => esc_html__('Background Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#ffffff',
											'wrapper-class'=>'color-wrapper background-type-wrapper'
										),		
										'skin' => array(
											'title' => esc_html__('Skin', 'limoking'),
											'type' => 'combobox',
											'options' => limoking_get_skin_list(),
											'description' => esc_html__('Can be created at the Theme Options > Elements Color > Custom Skin section', 'limoking')
										),
										'show-section'=> array(
											'title' => esc_html__('Show This Section In', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'gdlr-show-all' => esc_html__('All Devices', 'limoking'),
												'limoking-hide-in-tablet' => esc_html__('Hide This Section In Tablet', 'limoking'),
												'limoking-hide-in-mobile' => esc_html__('Hide This Section In Mobile', 'limoking'),
												'limoking-hide-in-tablet-mobile' => esc_html__('Hide This Section In Tablet and Mobile', 'limoking'),
											),
										),										
										'border'=> array(
											'title' => esc_html__('Border', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'none' => esc_html__('None', 'limoking'),
												'top' => esc_html__('Border Top', 'limoking'),
												'bottom' => esc_html__('Border Bottom', 'limoking'),
												'both' => esc_html__('Both Border', 'limoking'),
											),
										),
										'border-top-color' => array(
											'title' => esc_html__('Border Top Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper top-wrapper both-wrapper'
										),
										'border-bottom-color' => array(
											'title' => esc_html__('Border Bottom Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper bottom-wrapper both-wrapper'
										),
										'padding-top' => array(
											'title' => esc_html__('Padding Top', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['top-wrapper'],
											'description' => esc_html__('Spaces before starting any content in this section', 'limoking')
										),	
										'padding-bottom' => array(
											'title' => esc_html__('Padding Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-wrapper'],
											'description' => esc_html__('Spaces after ending of the content in this section', 'limoking')
										),
									)
								),
								
								'parallax-bg-wrapper' => array(
									'title'=> esc_html__('Background/Parallax Wrapper', 'limoking'), 
									'type'=>'wrapper',
									'options'=>array(
										'type' => array(
											'title' => esc_html__('Type', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'image'=> esc_html__('Background Image', 'limoking'),
												'pattern'=> esc_html__('Predefined Pattern', 'limoking'),
												'video'=> esc_html__('Video Background', 'limoking'),
											),
											'default'=>'image'
										),								
										'background' => array(
											'title' => esc_html__('Background Image', 'limoking'),
											'button' => esc_html__('Upload', 'limoking'),
											'type' => 'upload',
											'wrapper-class' => 'type-wrapper image-wrapper'
										),								
										'background-mobile' => array(
											'title' => esc_html__('Background Mobile', 'limoking'),
											'button' => esc_html__('Upload', 'limoking'),
											'type' => 'upload',
											'wrapper-class' => 'type-wrapper image-wrapper'
										),							
										'background-repeat' => array(
											'title' => esc_html__('Background Repeat', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'repeat' => esc_html__('Repeat', 'limoking'),
												'repeat-x' => esc_html__('Repeat X', 'limoking'),
												'repeat-y' => esc_html__('Repeat Y', 'limoking'),
												'no-repeat' => esc_html__('No Repeat', 'limoking'),
											),
											'wrapper-class' => 'type-wrapper image-wrapper'
										),
										'background-speed' => array(
											'title' => esc_html__('Background Speed', 'limoking'),
											'type' => 'text',
											'default' => '0',
											'wrapper-class' => 'type-wrapper image-wrapper',
											'description' => esc_html__('Fill 0 if you don\'t want the background to scroll and 1 when you want the background to have the same speed as the scroll bar', 'limoking') .
												'<br><br><strong>' . esc_html__('*** only allow the number between -1 to 1', 'limoking') . '</strong>'
										),		
										'pattern' => array(
											'title' => esc_html__('Pattern', 'limoking'),
											'type' => 'radioimage',
											'options' => array(
												'1'=>get_template_directory_uri() . '/include/images/pattern/pattern-1.png',
												'2'=>get_template_directory_uri() . '/include/images/pattern/pattern-2.png', 
												'3'=>get_template_directory_uri() . '/include/images/pattern/pattern-3.png',
												'4'=>get_template_directory_uri() . '/include/images/pattern/pattern-4.png',
												'5'=>get_template_directory_uri() . '/include/images/pattern/pattern-5.png',
												'6'=>get_template_directory_uri() . '/include/images/pattern/pattern-6.png',
												'7'=>get_template_directory_uri() . '/include/images/pattern/pattern-7.png',
												'8'=>get_template_directory_uri() . '/include/images/pattern/pattern-8.png'
											),
											'wrapper-class' => 'type-wrapper pattern-wrapper',
											'default' => '1'
										),		
										'video' => array(
											'title' => esc_html__('Youtube URL', 'limoking'),
											'type' => 'text',
											'wrapper-class' => 'type-wrapper video-wrapper'
										),
										'video-overlay' => array(
											'title' => esc_html__('Video Overlay Opacity', 'limoking'),
											'type' => 'text',
											'default' => '0.5',
											'wrapper-class' => 'type-wrapper video-wrapper'
										),
										'video-player' => array(
											'title' => esc_html__('Video Control Bar', 'limoking'),
											'type' => 'checkbox',
											'default' => 'enable',
											'wrapper-class' => 'type-wrapper video-wrapper'
										),
										'skin' => array(
											'title' => esc_html__('Skin', 'limoking'),
											'type' => 'combobox',
											'options' => limoking_get_skin_list(),
											'description' => esc_html__('Can be created at the Theme Options > Elements Color > Custom Skin section', 'limoking')
										),
										'show-section'=> array(
											'title' => esc_html__('Show This Section In', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'gdlr-show-all' => esc_html__('All Devices', 'limoking'),
												'limoking-hide-in-tablet' => esc_html__('Hide This Section In Tablet', 'limoking'),
												'limoking-hide-in-mobile' => esc_html__('Hide This Section In Mobile', 'limoking'),
												'limoking-hide-in-tablet-mobile' => esc_html__('Hide This Section In Tablet and Mobile', 'limoking'),
											),
										),										
										'border'=> array(
											'title' => esc_html__('Border', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'none' => esc_html__('None', 'limoking'),
												'top' => esc_html__('Border Top', 'limoking'),
												'bottom' => esc_html__('Border Bottom', 'limoking'),
												'both' => esc_html__('Both Border', 'limoking'),
											),
										),
										'border-top-color' => array(
											'title' => esc_html__('Border Top Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper top-wrapper both-wrapper'
										),
										'border-bottom-color' => array(
											'title' => esc_html__('Border Bottom Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper bottom-wrapper both-wrapper'
										),	
										'padding-top' => array(
											'title' => esc_html__('Padding Top', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['top-wrapper'],
											'description' => esc_html__('Spaces before starting any content in this section', 'limoking')
										),	
										'padding-bottom' => array(
											'title' => esc_html__('Padding Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-wrapper'],
											'description' => esc_html__('Spaces after ending of the content in this section', 'limoking')
										),
									)
								),
								
								'full-size-wrapper' => array(
									'title'=> esc_html__('Full Size Wrapper', 'limoking'), 
									'type'=>'wrapper',
									'options'=>array(
										'skin' => array(
											'title' => esc_html__('Skin', 'limoking'),
											'type' => 'combobox',
											'options' => limoking_get_skin_list(),
											'description' => esc_html__('Can be created at the Theme Options > Elements Color > Custom Skin section', 'limoking')
										),
										'background' => array(
											'title' => esc_html__('Background Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#ffffff'
										),
										'show-section'=> array(
											'title' => esc_html__('Show This Section In', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'gdlr-show-all' => esc_html__('All Devices', 'limoking'),
												'limoking-hide-in-tablet' => esc_html__('Hide This Section In Tablet', 'limoking'),
												'limoking-hide-in-mobile' => esc_html__('Hide This Section In Mobile', 'limoking'),
												'limoking-hide-in-tablet-mobile' => esc_html__('Hide This Section In Tablet and Mobile', 'limoking'),
											),
										),	
										'border'=> array(
											'title' => esc_html__('Border', 'limoking'),
											'type' => 'combobox',
											'options' => array(
												'none' => esc_html__('None', 'limoking'),
												'top' => esc_html__('Border Top', 'limoking'),
												'bottom' => esc_html__('Border Bottom', 'limoking'),
												'both' => esc_html__('Both Border', 'limoking'),
											),
										),
										'border-top-color' => array(
											'title' => esc_html__('Border Top Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper top-wrapper both-wrapper'
										),
										'border-bottom-color' => array(
											'title' => esc_html__('Border Bottom Color', 'limoking'),
											'type' => 'colorpicker',
											'default'=> '#e9e9e9',
											'wrapper-class'=> 'border-wrapper bottom-wrapper both-wrapper'
										),
										'padding-top' => array(
											'title' => esc_html__('Padding Top', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['top-full-wrapper'],
											'description' => esc_html__('Spaces before starting any content in this section', 'limoking')
										),	
										'padding-bottom' => array(
											'title' => esc_html__('Padding Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-wrapper'],
											'description' => esc_html__('Spaces after ending of the content in this section', 'limoking')
										),
									)
								)
							)
						),
						
						'content-item' => array(
							'title' => esc_html__('Content/Post Type Item', 'limoking'),
							'blank_option' => esc_html__('- Select Content Item -', 'limoking'),
							'options' => array(

								'about-us' => array(
									'title'=> esc_html__('About Us', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'style'=> array(
											'title'=> esc_html__('Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'plain'=> esc_html__('Plain' ,'limoking'),	
												'with-caption'=> esc_html__('With Caption' ,'limoking'),	
												'with-divider'=> esc_html__('With Divider' ,'limoking'),	
											)
										),	
										'title'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',						
										),
										'caption'=> array(
											'title'=> esc_html__('Caption' ,'limoking'),
											'type'=> 'textarea',
											'wrapper-class'=> 'style-wrapper with-caption-wrapper'
										),
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),
										'read-more-type'=> array(
											'title'=> esc_html__('Read More Type' ,'limoking'),
											'type'=> 'combobox',					
											'options'=> array(
												'url' => esc_html__('URL', 'limoking'),
												'car-form' => esc_html__('Car Contact Form', 'limoking')
											)
										),
										'read-more-text'=> array(
											'title'=> esc_html__('Read More Text' ,'limoking'),
											'type'=> 'text',					
											'default'=> esc_html__('Read More' ,'limoking')
										),		
										'read-more-link'=> array(
											'title'=> esc_html__('Read More Link' ,'limoking'),
											'type'=> 'text',
											'wrapper-class'=>'read-more-type-wrapper url-wrapper'
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	 
									)
								),	
								
								'accordion' => array(
									'title'=> esc_html__('Accordion', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'accordion'=> array(
											'type'=> 'tab',
											'default-title'=> esc_html__('Accordion' ,'limoking')											
										)
									), limoking_page_builder_title_option(), array(
										'initial-state'=> array(
											'title'=> esc_html__('Initial Open', 'limoking'),
											'type'=> 'text',
											'default'=> 1,
											'description'=> esc_html__('0 will close all tab as an initial state, 1 will open the first tab and so on.', 'limoking')						
										),		
										'style'=> array(
											'title'=> esc_html__('Accordion Style' ,'limoking'),
											'type' => 'combobox',
											'options' => array(
												'style-1' => esc_html__('Style 1 ( Colored Background )', 'limoking'),
												'style-2' => esc_html__('Style 2 ( Transparent Background )', 'limoking')
											)
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),
									))
								), 					
								
								'blog' => array(
									'title'=> esc_html__('Blog', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(limoking_page_builder_title_option(true), array(										
										'category'=> array(
											'title'=> esc_html__('Category' ,'limoking'),
											'type'=> 'multi-combobox',
											'options'=> limoking_get_term_list('category'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'limoking')
										),	
										'tag'=> array(
											'title'=> esc_html__('Tag' ,'limoking'),
											'type'=> 'multi-combobox',
											'options'=> limoking_get_term_list('post_tag'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'limoking')
										),	
										'num-excerpt'=> array(
											'title'=> esc_html__('Num Excerpt (Word)' ,'limoking'),
											'type'=> 'text',	
											'default'=> '25',
											'description'=> esc_html__('This is a number of word (decided by spaces) that you want to show on the post excerpt. <strong>Use 0 to hide the excerpt, -1 to show full posts and use the wordpress more tag</strong>.', 'limoking')
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch' ,'limoking'),
											'type'=> 'text',	
											'default'=> '8',
											'description'=> esc_html__('Specify the number of posts you want to pull out.', 'limoking')
										),										
										'blog-style'=> array(
											'title'=> esc_html__('Blog Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'blog-widget-1-4' => '1/4 ' . esc_html__('Blog Widget', 'limoking'),
												'blog-widget-1-3' => '1/3 ' . esc_html__('Blog Widget', 'limoking'),
												'blog-widget-1-2' => '1/2 ' . esc_html__('Blog Widget', 'limoking'),
												'blog-widget-1-1' => '1/1 ' . esc_html__('Blog Widget', 'limoking'),
												'blog-1-4' => '1/4 ' . esc_html__('Blog Grid', 'limoking'),
												'blog-1-3' => '1/3 ' . esc_html__('Blog Grid', 'limoking'),
												'blog-1-2' => '1/2 ' . esc_html__('Blog Grid', 'limoking'),
												'blog-1-1' => '1/1 ' . esc_html__('Blog Grid', 'limoking'),
												'blog-medium' => esc_html__('Blog Medium', 'limoking'),
												'blog-full' => esc_html__('Blog Full', 'limoking'),
											),
											'default'=>'blog-1-1'
										),		
										'blog-layout'=> array(
											'title'=> esc_html__('Blog Layout Order' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'fitRows' =>  esc_html__('FitRows ( Order items by row )', 'limoking'),
												'masonry' => esc_html__('Masonry ( Order items by spaces )', 'limoking'),
												'carousel' => esc_html__('Carousel ( Only For Blog Grid )', 'limoking'),
											),
											'wrapper-class'=> 'blog-1-4-wrapper blog-1-3-wrapper blog-1-2-wrapper blog-style-wrapper',
											'description'=> esc_html__('You can see an example of these two layout here', 'limoking') . 
												'<br>http://isotope.metafizzy.co/demos/layout-modes.html'
										),
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list(),
											'description'=> esc_html__('Only effects to <strong>standard and gallery post format</strong>.','limoking')
										),	
										'orderby'=> array(
											'title'=> esc_html__('Order By' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'date' => esc_html__('Publish Date', 'limoking'), 
												'title' => esc_html__('Title', 'limoking'), 
												'rand' => esc_html__('Random', 'limoking'), 
											)
										),
										'order'=> array(
											'title'=> esc_html__('Order' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'desc'=>esc_html__('Descending Order', 'limoking'), 
												'asc'=> esc_html__('Ascending Order', 'limoking'), 
											)
										),	
										'offset'=> array(
											'title'=> esc_html__('Offset' ,'limoking'),
											'type'=> 'text',
											'description'=> esc_html__('Fill in number of the posts you want to skip. Please noted that this will not works well with pagination', 'limoking')
										),										
										'pagination'=> array(
											'title'=> esc_html__('Enable Pagination' ,'limoking'),
											'type'=> 'checkbox'
										),	
										'enable-sticky'=> array(
											'title'=> esc_html__('Prepend Sticky Post' ,'limoking'),
											'type'=> 'checkbox',
											'default'=> 'disable'
										),											
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-blog-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									))
								),								

								'box-icon-item' => array(
									'title'=> esc_html__('Box Icon', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'limoking'),
											'type'=> 'text',						
										),		
										'icon-position'=> array(
											'title'=> esc_html__('Icon Position' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'left'=> esc_html__('Left', 'limoking'),
												'top'=> esc_html__('Top', 'limoking')
											)
										),			
										'icon-type'=> array(
											'title'=> esc_html__('Icon Type' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'normal'=> esc_html__('Normal', 'limoking'),
												'circle'=> esc_html__('Circle Background', 'limoking')
											)					
										),	
										'icon-color'=> array(
											'title'=> esc_html__('Icon Color' ,'limoking'),
											'type'=> 'colorpicker',		
											'default'=> '#5e5e5e'
										),	
										'icon-background'=> array(
											'title'=> esc_html__('Icon Background' ,'limoking'),
											'type'=> 'colorpicker',		
											'default'=> '#91d549',
											'wrapper-class'=> 'icon-type-wrapper circle-wrapper'
										),
										'title'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',						
										),										
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	 
									)
								),								
								
								'car' => array(),
								
								'car-rate' => array(),
								
								'column-service' => array(
									'title'=> esc_html__('Column Service', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'type'=> array(
											'title'=> esc_html__('Type' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'no-media'=> esc_html__('No Media' ,'limoking'),	
												'image'=> esc_html__('Image' ,'limoking'),	
												'icon'=> esc_html__('Icon' ,'limoking'),	
											)
										),
										'size'=> array(
											'title'=> esc_html__('Item Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'medium' => esc_html__('Medium' ,'limoking'),	
												'large' => esc_html__('Large' ,'limoking'),	
											)
										),											
										'image' => array(
											'title' => esc_html__('Image', 'limoking'),
											'button' => esc_html__('Upload', 'limoking'),
											'type' => 'upload',
											'wrapper-class' => 'type-wrapper image-wrapper'
										),	
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'limoking'),
											'type'=> 'text',
											'wrapper-class' => 'type-wrapper icon-wrapper'
										),				
										'caption'=> array(
											'title'=> esc_html__('Caption' ,'limoking'),
											'type'=> 'textarea',
											'wrapper-class'=> 'type-wrapper no-media-wrapper'
										),		
										'title'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',						
										),								
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),		
										'read-more-text'=> array(
											'title'=> esc_html__('Read More Text' ,'limoking'),
											'type'=> 'text',					
											'default'=> esc_html__('Read More' ,'limoking')
										),		
										'read-more-link'=> array(
											'title'=> esc_html__('Read More Link' ,'limoking'),
											'type'=> 'text',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	 
									)
								),								
								
								'content' => array(
									'title'=> esc_html__('Content', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(limoking_page_builder_title_option(true), array(	
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),														
									))
								), 	

								'divider' => array(
									'title'=> esc_html__('Divider', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'type'=> array(
											'title'=> esc_html__('Divider', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'solid' => esc_html__('Solid', 'limoking'),
												'double' => esc_html__('Double', 'limoking'),
												'dotted' => esc_html__('Dotted', 'limoking'),
												'double-dotted' => esc_html__('Double Dotted', 'limoking'),
												'thick' => esc_html__('Thick', 'limoking'),
												'with-icon' => esc_html__('With Icon', 'limoking'),
											)
										),	
										'divider-color'=> array(	
											'title'=> esc_html__('Icon Background Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#333333',
											'wrapper-class'=> 'type-wrapper with-icon-wrapper',
										),	
										'icon-class'=> array(	
											'title'=> esc_html__('Icon Class' ,'limoking'),
											'type'=> 'text',
											'wrapper-class'=> 'type-wrapper with-icon-wrapper',
										),	
										'size'=> array(	
											'title'=> esc_html__('Divider Width' ,'limoking'),
											'type'=> 'text',
											'description'=> esc_html__('Specify the divider size. Ex. 50%, 200px', 'limoking')
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-divider-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									)
								),

								'feature-media' => array(
									'title'=> esc_html__('Feature Media', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'type'=> array(
											'title'=> esc_html__('Media Type' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'image'=> esc_html__('Image' ,'limoking'),
												'video'=> esc_html__('Video' ,'limoking')
											)
										),
										'video-url'=> array(
											'title'=> esc_html__('Video URL' ,'limoking'),
											'type'=> 'text',
											'wrapper-class'=> 'type-wrapper video-wrapper'
										),
										'image'=> array(
											'title'=> esc_html__('Service Image' ,'limoking'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'limoking'),	
											'wrapper-class'=> 'type-wrapper image-wrapper'
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list(),	
											'wrapper-class'=> 'type-wrapper image-wrapper'
										)
									), limoking_page_builder_title_option(), array(								
										'align'=> array(
											'title'=> esc_html__('Alignment' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'left' => esc_html__('Left' ,'limoking'),
												'center' => esc_html__('Center' ,'limoking'),
											),
											'default'=> 'left'
										),										
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),	
										'button-text'=> array(
											'title'=> esc_html__('Learn More Button Text' ,'limoking'),
											'type'=> 'text',
											'default'=> esc_html__('Learn More', 'limoking')
										),		
										'button-link'=> array(
											'title'=> esc_html__('Learn More Button Link' ,'limoking'),
											'type'=> 'text'
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	 
									))
								),
								
								'icon-with-list' => array(
									'title'=> esc_html__('List With Icon', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'icon-with-list'=> array(
											'type'=> 'icon-with-list',
											'default-title'=> esc_html__('Icon With List' ,'limoking')
										),	
									), limoking_page_builder_title_option(), array(
										'align'=> array(	
											'title'=> esc_html__('Text Align' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'left'=> esc_html__('Left Aligned' ,'limoking'),
												'right'=> esc_html__('Right Aligned' ,'limoking')
											)
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									))
								), 		
								
								'menu' => array(
									'title'=> esc_html__('Menu List', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'content'=> array(
											'default-title'=> esc_html__('Menu', 'limoking'),
											'type'=> 'custom_chart',
											'options'=> array(
												'gdl-tab-title' => esc_html__('Title', 'limoking') . ':text',
												'caption' => esc_html__('Caption', 'limoking') . ':text',
												'price' => esc_html__('Price', 'limoking') . ':text',
												'icon' => esc_html__('Icon', 'limoking') . ':text'
											)
										),
									), limoking_page_builder_title_option(), array(											
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									))
								), 
								
								'notification' => array(
									'title'=> esc_html__('Notification', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'icon'=> array(	
											'title'=> esc_html__('Icon Class', 'limoking'),
											'type'=> 'text'										
										),
										'type'=> array(	
											'title'=> esc_html__('Type', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'color-background'=> esc_html__('Color Background', 'limoking'),
												'color-border'=> esc_html__('Color Border', 'limoking'),
											)											
										),
										'content'=> array(	
											'title'=> esc_html__('Content', 'limoking'),
											'type'=> 'textarea'										
										),
										'color'=> array(	
											'title'=> esc_html__('Text Color', 'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#000000'											
										),
										'background'=> array(	
											'title'=> esc_html__('Background Color', 'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#99d15e',
											'wrapper-class'=> 'type-wrapper color-background-wrapper'
										),
										'border'=> array(	
											'title'=> esc_html__('Border Color', 'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#99d15e',
											'wrapper-class'=> 'type-wrapper color-border-wrapper'											
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),
									)
								),

							'page'=> array(
									'title'=> esc_html__('Page', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(limoking_page_builder_title_option(true), array(
										'category'=> array(
											'title'=> esc_html__('Category' ,'limoking'),
											'type'=> 'multi-combobox',
											'options'=> limoking_get_term_list('page_category'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'limoking')
										),	
										'page-style'=> array(
											'title'=> esc_html__('Item Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'classic' => esc_html__('Classic Style', 'limoking'),
												'modern' => esc_html__('Modern Style', 'limoking'),
											),
										),	
										'item-size'=> array(
											'title'=> esc_html__('Item Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'1/4'=>'1/4',
												'1/3'=>'1/3',
												'1/2'=>'1/2',
												'1/1'=>'1/1'
											),
											'default'=>'1/3'
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch' ,'limoking'),
											'type'=> 'text',	
											'default'=> '8',
											'description'=> esc_html__('Specify the number of page you want to pull out.', 'limoking')
										),																			
										'page-layout'=> array(
											'title'=> esc_html__('Page Layout Order' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'fitRows' =>  esc_html__('FitRows ( Order items by row )', 'limoking'),
												'masonry' => esc_html__('Masonry ( Order items by spaces )', 'limoking'),
											),
											'description'=> esc_html__('You can see an example of these two layout here', 'limoking') . 
												'<br><br> http://isotope.metafizzy.co/demos/layout-modes.html'
										),					
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list(),
											'description'=> esc_html__('Only effects to <strong>standard and gallery post format</strong>','limoking')
										),		
										'pagination'=> array(
											'title'=> esc_html__('Enable Pagination' ,'limoking'),
											'type'=> 'checkbox'
										),					
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-blog-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),				
									))
								),	
								
								'personnel' => array(
									'title'=> esc_html__('Personnel', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'personnel'=> array(	
											'type'=> 'authorinfo',
											'default-title'=> esc_html__('Personnel' ,'limoking')											
										)
									), limoking_page_builder_title_option(), array(											
										'personnel-columns'=> array(
											'title'=> esc_html__('Personnel Columns' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'),
											'default'=> '3'
										),				
										'personnel-type'=> array(
											'title'=> esc_html__('Personnel Type' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'static'=>esc_html__('Static Personnel', 'limoking'),
												'carousel'=>esc_html__('Carousel Personnel', 'limoking'),
											)
										),		
										'personnel-style'=> array(
											'title'=> esc_html__('Personnel Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'box-style'=>esc_html__('Box Style', 'limoking'),
												'modern-style'=>esc_html__('Modern Style', 'limoking'),
												'plain-style'=>esc_html__('Plain Style', 'limoking'),
												'limoking-left plain-style'=>esc_html__('Plain Left Style', 'limoking'),
												'round-style'=>esc_html__('Round Style', 'limoking'),
											)
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Author Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list(),
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),												
									))
								),		

								'pie-chart' => array(
									'title'=> esc_html__('Pie Chart', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'progress'=> array(
											'title'=> esc_html__('Progress (Percent)' ,'limoking'),
											'type'=> 'text',
											'default'=> '50',
											'description'=> esc_html__('Accept integer value between 0 - 100', 'limoking')
										),		
										'color'=> array(
											'title'=> esc_html__('Progress Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#f5be3b'
										),
										'bg-color'=> array(
											'title'=> esc_html__('Progress Track Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#f2f2f2'						
										),										
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'limoking'),
											'type'=> 'text',						
										),	
										'title'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',						
										),			
										'learn-more-link'=> array(
											'title'=> esc_html__('Learn More Link' ,'limoking'),
											'type'=> 'text',						
										),											
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),											
									)
								),		
						
								'portfolio' => array(),
								
								'price-item' => array(
									'title'=> esc_html__('Price Item', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'image'=> array(
											'title'=> esc_html__('Image' ,'limoking'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'limoking'),				
										),	
										'title' => array(
											'title' => esc_html__('Title', 'limoking'),
											'type' => 'text',
										),	
										'price' => array(
											'title' => esc_html__('Price', 'limoking'),
											'type' => 'text',
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									)
								),
								
								'price-table' => array(
									'title'=> esc_html__('Price Table', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'price-table'=> array(	
											'type'=> 'price-table',
											'default-title'=> esc_html__('Price Table' ,'limoking')											
										),
										'columns'=> array(	
											'title' => esc_html__('Columns', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6),
											'default'=> 3
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									)
								),
								
								'service-half-background' => array(
									'title'=> esc_html__('Service Half Background', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'left-bg-image'=> array(
											'title'=> esc_html__('Left Service Background Image' ,'limoking'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'limoking'),				
										),
										'left-title'=> array(
											'title'=> esc_html__('Left Service Title' ,'limoking'),
											'type'=> 'text',						
										),													
										'left-content'=> array(
											'title'=> esc_html__('Left Service Content' ,'limoking'),
											'type'=> 'textarea',						
										),													
										'left-read-more-text'=> array(
											'title'=> esc_html__('Left Read More Text' ,'limoking'),
											'type'=> 'text',	
											'default'=> esc_html__('Read More', 'limoking')
										),													
										'left-read-more-link'=> array(
											'title'=> esc_html__('Left Read More Link' ,'limoking'),
											'type'=> 'text',						
										),
										'right-bg-color'=> array(
											'title'=> esc_html__('Right Service Background Color' ,'limoking'),
											'type'=> 'colorpicker'			
										),
										'right-title'=> array(
											'title'=> esc_html__('Right Service Title' ,'limoking'),
											'type'=> 'text',						
										),													
										'right-content'=> array(
											'title'=> esc_html__('Right Service Content' ,'limoking'),
											'type'=> 'textarea',						
										),													
										'right-read-more-text'=> array(
											'title'=> esc_html__('Right Read More Text' ,'limoking'),
											'type'=> 'text',	
											'default'=> esc_html__('Read More', 'limoking')						
										),													
										'right-read-more-link'=> array(
											'title'=> esc_html__('Right Read More Link' ,'limoking'),
											'type'=> 'text',						
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	 
									)
								),
								
								'service-with-image' => array(
									'title'=> esc_html__('Service With Image', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'image'=> array(
											'title'=> esc_html__('Service Image' ,'limoking'),
											'type'=> 'upload',						
											'button'=> esc_html__('upload' ,'limoking'),				
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list()
										),		
										'align'=> array(
											'title'=> esc_html__('Item Alignment' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'left'=> esc_html__('Left Aligned' ,'limoking'),
												'right'=> esc_html__('Right Aligned' ,'limoking')
											)
										),
										'title'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',						
										),													
										'content'=> array(
											'title'=> esc_html__('Content Text' ,'limoking'),
											'type'=> 'tinymce',						
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	 
									)
								),
								
								'stunning-text' => array(
									'title'=> esc_html__('Stunning Text', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'title'=> array(
											'title'=> esc_html__('Stunning Text title', 'limoking'),
											'type'=> 'text'
										),		
										'caption'=> array(
											'title'=> esc_html__('Stunning Text Caption' ,'limoking'),
											'type'=> 'textarea'
										),
										'read-more-type'=> array(
											'title'=> esc_html__('Button Type' ,'limoking'),
											'type'=> 'combobox',					
											'options'=> array(
												'url' => esc_html__('URL', 'limoking'),
												'car-form' => esc_html__('Car Contact Form', 'limoking')
											)
										),
										'button-text'=> array(
											'title'=> esc_html__('Stunning Button Text' ,'limoking'),
											'type'=> 'text',
											'default'=> esc_html__('Learn More', 'limoking')
										),		
										'button-link'=> array(
											'title'=> esc_html__('Stunning Button Link' ,'limoking'),
											'type'=> 'text',
											'wrapper-class'=>'read-more-type-wrapper url-wrapper'
										),		
										'button-text-color'=> array(
											'title'=> esc_html__('Stunning Button Text Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#fff'
										),		
										'button-background'=> array(
											'title'=> esc_html__('Stunning Button Background Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#009fbd'
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => 0,
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									)
								),			

								'skill-bar' => array(
									'title'=> esc_html__('Skill Bar', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'content'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',
										),
										'percent'=> array(
											'title'=> esc_html__('Percent' ,'limoking'),
											'type'=> 'text',
											'default'=> '0',
											'description'=> esc_html__('Fill only number here', 'limoking')
										),	
										'size'=> array(
											'title'=> esc_html__('Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'small'=> esc_html__('Small' ,'limoking'),
												'medium'=> esc_html__('Medium' ,'limoking'),
												'large'=> esc_html__('Large' ,'limoking'),
											)
										),	
										'icon'=> array(
											'title'=> esc_html__('Icon Class' ,'limoking'),
											'type'=> 'text',
											'wrapper-class'=> 'size-wrapper medium-wrapper large-wrapper'
										),	
										'text-color'=> array(
											'title'=> esc_html__('Text Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'background-color'=> array(
											'title'=> esc_html__('Background Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#e9e9e9'
										),												
										'progress-color'=> array(
											'title'=> esc_html__('Progress Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#f5be3b'
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									)
								),
								
								'skill-item' => array(
									'title'=> esc_html__('Skill Item', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'style'=> array(
											'title'=> esc_html__('Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'style-1'=> esc_html__('Style 1' ,'limoking'),
												'style-2'=> esc_html__('Style 2' ,'limoking'),
											)
										),
										'icon-color'=> array(
											'title'=> esc_html__('Icon Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'title-color'=> array(
											'title'=> esc_html__('Title Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'caption-color'=> array(
											'title'=> esc_html__('Caption Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#ffffff'
										),
										'icon-class'=> array(
											'title'=> esc_html__('Icon Class' ,'limoking'),
											'type'=> 'text',
											'wrapper-class'=> 'style-2-wrapper style-wrapper'
										),
										'title'=> array(
											'title'=> esc_html__('Title' ,'limoking'),
											'type'=> 'text',
										),
										'caption'=> array(
											'title'=> esc_html__('Caption' ,'limoking'),
											'type'=> 'text',
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),
									)
								),
								
								'styled-box' => array(
									'title'=> esc_html__('Styled Box', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'type'=> array(
											'title'=> esc_html__('Background Type', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'color'=> esc_html__('Color Background' ,'limoking'),
												'image'=> esc_html__('Image Background' ,'limoking'),
											)
										),	
										'flip-corner'=> array(
											'title'=> esc_html__('Flip Corner' ,'limoking'),
											'type'=> 'checkbox',
											'wrapper-class'=> 'type-wrapper color-wrapper'
										),										
										'background-color'=> array(
											'title'=> esc_html__('Background Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#9ada55',
											'wrapper-class'=> 'type-wrapper color-wrapper'
										),												
										'corner-color'=> array(
											'title'=> esc_html__('Corner Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#3d6817',
											'wrapper-class'=> 'type-wrapper color-wrapper'
										),		
										'content-color'=> array(
											'title'=> esc_html__('Content Color' ,'limoking'),
											'type'=> 'colorpicker',
											'default'=> '#dddddd'
										),											
										'background-image'=> array(
											'title'=> esc_html__('Image URL' ,'limoking'),
											'type'=> 'upload',
											'button' => esc_html__('Upload', 'limoking'),
											'wrapper-class'=> 'type-wrapper image-wrapper'
										),										
										'content'=> array(
											'title'=> esc_html__('Content' ,'limoking'),
											'type'=> 'tinymce'
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									)
								),									
								
								'testimonial' => array(
									'title'=> esc_html__('Testimonial', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'testimonial'=> array(	
											'type'=> 'authorinfo',
											'enable-social'=> 'false',
											'default-title'=> esc_html__('Testimonial' ,'limoking')											
										),
									), limoking_page_builder_title_option(), array(													
										'testimonial-columns'=> array(
											'title'=> esc_html__('Testimonial Columns' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5'),
											'default'=> '3'
										),				
										'testimonial-type'=> array(
											'title'=> esc_html__('Testimonial Type' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'static'=>esc_html__('Static Testimonial', 'limoking'),
												'carousel'=>esc_html__('Carousel Testimonial', 'limoking'),
											)
										),		
										'testimonial-style'=> array(
											'title'=> esc_html__('Testimonial Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'box-style'=>esc_html__('Box Style', 'limoking'),
												'modern-style'=>esc_html__('Modern Style', 'limoking'),
												'round-style'=>esc_html__('Round Style', 'limoking'),
												'plain-style'=>esc_html__('Plain Style', 'limoking'),
												'large plain-style'=>esc_html__('Large Plain Style', 'limoking'),
												'limoking-left plain-style'=>esc_html__('Plain Left Style', 'limoking'),
												'limoking-left large plain-style'=>esc_html__('Large Plain Left Style', 'limoking'),
											)
										),		
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),											
									))
								),								
								
								'tab' => array(
									'title'=> esc_html__('Tab', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'tab'=> array(
											'type'=> 'tab',
											'default-title'=> esc_html__('Tab' ,'limoking')
										),					
										'initial-state'=> array(
											'title'=> esc_html__('Initial Tab', 'limoking'),
											'type'=> 'text',
											'default'=> 1,
											'description'=> esc_html__('1 will open the first tab, 2 for second tab and so on.', 'limoking')						
										),		
										'style'=> array(
											'title'=> esc_html__('Tab Style' ,'limoking'),
											'type' => 'combobox',
											'options' => array(
												'horizontal' => esc_html__('Horizontal Tab', 'limoking'),
												'vertical' => esc_html__('Vertical Tab', 'limoking'),
												'vertical right' => esc_html__('Vertical Right Tab', 'limoking')
											)
										)	
									), limoking_page_builder_title_option(), array(											
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									))
								), 								

								'title' => array(
									'title'=> esc_html__('Title', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(limoking_page_builder_title_option(true), array(						
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									))
								),
								
								'toggle-box' => array(
									'title'=> esc_html__('Toggle Box', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(
										'toggle-box'=> array(
											'type'=> 'toggle-box',
											'default-title'=> esc_html__('Toggle Box' ,'limoking')							
										),
									), limoking_page_builder_title_option(), array(										
										'style'=> array(
											'title'=> esc_html__('Accordion Style' ,'limoking'),
											'type' => 'combobox',
											'options' => array(
												'style-1' => esc_html__('Style 1 ( Colored Background )', 'limoking'),
												'style-2' => esc_html__('Style 2 ( Transparent Background )', 'limoking')
											)
										),	
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									))
								), 								
								 					
							)
						),
						
						'media-item' => array(
							'title' => esc_html__('Media Item', 'limoking'),
							'blank_option' => esc_html__('- Select Media Item -', 'limoking'),
							'options' => array(
							
								'banner' => array(
									'title'=> esc_html__('Banner', 'limoking'), 
									'type'=>'item',
									'options'=>array(									
										'slider'=> array(	
											'overlay'=> 'false',
											'caption'=> 'false',
											'type'=> 'slider',
										),										
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list()
										),
										'banner-columns'=> array(
											'title'=> esc_html__('Banner Image Columns' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6'),
											'default'=> '4'
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),										
									)	
								),								

								'gallery' => array(
									'title'=> esc_html__('Gallery', 'limoking'), 
									'type'=>'item',
									'options'=> array_merge(array(								
										'slider'=> array(	
											'overlay'=> 'false',
											'caption'=> 'false',
											'type'=> 'slider',
										),				
									), limoking_page_builder_title_option(), array(												
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list()
										),
										'gallery-style'=> array(
											'title'=> esc_html__('Gallery Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'grid' => esc_html__('Grid Gallery', 'limoking'),
												'thumbnail' => esc_html__('Thumbnail Gallery', 'limoking')
											)
										),
										'gallery-columns'=> array(
											'title'=> esc_html__('Gallery Image Columns' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6'),
											'default'=> '4'
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch (Per Page)' ,'limoking'),
											'type'=> 'text',
											'description'=> esc_html__('Leave this field blank to fetch all image without pagination.', 'limoking'),
											'wrapper-class'=>'gallery-style-wrapper grid-wrapper'
										),
										'show-caption'=> array(
											'title'=> esc_html__('Show Caption' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array('yes'=>'Yes', 'no'=>'No')
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									))	
								),		
								
								'image-frame' => array(
									'title'=> esc_html__('Image / Frame', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'image-id'=> array(
											'title'=> esc_html__('Upload Image', 'limoking'),
											'type'=> 'upload',
											'button'=> esc_html__('Upload', 'limoking')
										),	
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list()
										),
										'link-type'=> array(
											'title'=> esc_html__('Image Link', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'none'=> esc_html__('None', 'limoking'),
												'content'=> esc_html__('Content', 'limoking'),
												'url'=> esc_html__('Link to Url', 'limoking'),
												'current'=> esc_html__('Lightbox to Current Image', 'limoking'),
												'image'=> esc_html__('Lightbox to Image', 'limoking'),
												'video'=> esc_html__('Lightbox to Video', 'limoking'),
											)
										),
										'title' => array(
											'title' => esc_html__('Title', 'limoking'),
											'type' => 'text',
											'wrapper-class' => 'link-type-wrapper content-wrapper'
										),
										'content' => array(
											'title' => esc_html__('Content', 'limoking'),
											'type' => 'textarea',
											'wrapper-class' => 'link-type-wrapper content-wrapper'
										),
										'url' => array(
											'title' => esc_html__('URL', 'limoking'),
											'type' => 'text',
											'wrapper-class' => 'link-type-wrapper image-wrapper video-wrapper url-wrapper'
										),
										'frame-type'=> array(
											'title'=> esc_html__('Frame Type', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'none'=> esc_html__('none', 'limoking'),
												'border'=> esc_html__('Border', 'limoking'),
												'solid'=> esc_html__('Solid', 'limoking'),
												'rounded'=> esc_html__('Round', 'limoking'),
												'circle'=> esc_html__('Circle', 'limoking')
											)
										),
										'frame-background' => array(
											'title' => esc_html__('Frame Background', 'limoking'),
											'type' => 'colorpicker',
											'default' => '#dddddd',
											'wrapper-class' => 'frame-type-wrapper solid-wrapper'
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),
									)
								),
								
								'layer-slider' => array(
									'title'=> esc_html__('Layer Slider', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'id'=> array(
											'title'=> esc_html__('Slider Type', 'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_layerslider_list(),
											'description'=> esc_html__('Please update layerslider to latest version to make this item work properly too', 'limoking')
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									)
								),

								'master-slider' => array(
									'title'=> esc_html__('Master Slider', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'id'=> array(
											'title'=> esc_html__('Slider Type', 'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_masterslider_list(),
											'description'=> esc_html__('Please update layerslider to latest version to make this item work properly too', 'limoking')
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									)
								),								

								'post-slider' => array(
									'title'=> esc_html__('Post Slider', 'limoking'), 
									'type'=>'item',
									'options'=>array(	
										'category'=> array(
											'title'=> esc_html__('Category' ,'limoking'),
											'type'=> 'multi-combobox',
											'options'=> limoking_get_term_list('category'),
											'description'=> esc_html__('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'limoking')
										),	
										'num-excerpt'=> array(
											'title'=> esc_html__('Num Excerpt (Word)' ,'limoking'),
											'type'=> 'text',	
											'default'=> '25',
											'description'=> esc_html__('This is a number of word (decided by spaces) that you want to show on the post excerpt. <strong>Use 0 to hide the excerpt, -1 to show full posts and use the wordpress more tag</strong>.', 'limoking')
										),	
										'num-fetch'=> array(
											'title'=> esc_html__('Num Fetch' ,'limoking'),
											'type'=> 'text',	
											'default'=> '8',
											'description'=> esc_html__('Specify the number of posts you want to pull out.', 'limoking')
										),										
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list()
										),	
										'style'=> array(
											'title'=> esc_html__('Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'no-excerpt'=>esc_html__('No Excerpt', 'limoking'),
												'with-excerpt'=>esc_html__('With Excerpt', 'limoking'),
											)
										),
										'caption-style'=> array(
											'title'=> esc_html__('Caption Style' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'post-bottom post-slider'=>esc_html__('Bottom Caption', 'limoking'),
												'post-right post-slider'=>esc_html__('Right Caption', 'limoking'),
												'post-left post-slider'=>esc_html__('Left Caption', 'limoking')
											),
											'wrapper-class' => 'style-wrapper with-excerpt-wrapper'
										),											
										'orderby'=> array(
											'title'=> esc_html__('Order By' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'date' => esc_html__('Publish Date', 'limoking'), 
												'title' => esc_html__('Title', 'limoking'), 
												'rand' => esc_html__('Random', 'limoking'), 
											)
										),
										'order'=> array(
											'title'=> esc_html__('Order' ,'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'desc'=>esc_html__('Descending Order', 'limoking'), 
												'asc'=> esc_html__('Ascending Order', 'limoking'), 
											)
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),											
									)
								),
								
								'slider' => array(
									'title'=> esc_html__('Slider', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'slider'=> array(	
											'overlay'=> 'false',
											'caption'=> 'true',
											'type'=> 'slider'						
										),	
										'slider-type'=> array(
											'title'=> esc_html__('Slider Type', 'limoking'),
											'type'=> 'combobox',
											'options'=> array(
												'flexslider' => esc_html__('Flex slider', 'limoking'),
												'nivoslider' => esc_html__('Nivo Slider', 'limoking')
											)
										),		
										'thumbnail-size'=> array(
											'title'=> esc_html__('Thumbnail Size' ,'limoking'),
											'type'=> 'combobox',
											'options'=> limoking_get_thumbnail_list()
										),			
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),											
									)
								),

								'video' => array(
									'title'=> esc_html__('Video', 'limoking'), 
									'type'=>'item',
									'options'=>array(
										'url'=> array(	
											'title'=> esc_html__('Video Url', 'limoking'),
											'type'=> 'text',
											'descirption'=> esc_html__('Youtube / Vimeo / Self Hosted Video Is allowed Here', 'limoking')
										),
										'margin-bottom' => array(
											'title' => esc_html__('Margin Bottom', 'limoking'),
											'type' => 'text',
											'default' => $limoking_spaces['bottom-item'],
											'description' => esc_html__('Spaces after ending of this item', 'limoking')
										),	
									)
								),															
								
							)
						)
					)
				)
			);
			
		}
		
	}
	
?>