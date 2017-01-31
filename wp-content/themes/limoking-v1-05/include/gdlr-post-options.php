<?php
	/*	
	*	Goodlayers Post Option file
	*	---------------------------------------------------------------------
	*	This file creates all post options to the post page
	*	---------------------------------------------------------------------
	*/
	
	// add a post admin option
	add_filter('limoking_admin_option', 'limoking_register_post_admin_option');
	if( !function_exists('limoking_register_post_admin_option') ){
		function limoking_register_post_admin_option( $array ){		
			if( empty($array['general']['options']) ) return $array;
			
			global $limoking_sidebar_controller;
			$post_option = array(
				'title' => esc_html__('Blog Style', 'limoking'),
				'options' => array(
					'post-title' => array(
						'title' => esc_html__('Default Post Title', 'limoking'),
						'type' => 'text',	
						'default' => 'Single Blog Title'
					),
					'post-caption' => array(
						'title' => esc_html__('Default Post Caption', 'limoking'),
						'type' => 'textarea',
						'default' => 'This is a single blog caption'
					),			
					'post-thumbnail-size' => array(
						'title' => esc_html__('Single Post Thumbnail Size', 'limoking'),
						'type'=> 'combobox',
						'options'=> limoking_get_thumbnail_list(),
						'default'=> 'post-thumbnail-size'
					),
					'post-meta-data' => array(
						'title' => esc_html__('Disable Post Meta Data', 'limoking'),
						'type'=> 'multi-combobox',
						'options'=> array(
							'date'=>'Date',
							'tag'=>'Tag',
							'category'=>'Category',
							'comment'=>'Comment',
							'author'=>'Author',
						),
						'description'=> esc_html__('Select this to remove the meta data out of the post.<br><br>', 'limoking') .
							esc_html__('You can use Ctrl/Command button to select multiple option or remove the selected option.', 'limoking')
					),
					'single-post-author' => array(
						'title' => esc_html__('Enable Single Post Author', 'limoking'),
						'type'=> 'checkbox'
					),
					'post-sidebar-template' => array(
						'title' => esc_html__('Default Post Sidebar', 'limoking'),
						'type' => 'radioimage',
						'options' => array(
							'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar.png',
							'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar.png', 
							'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar.png',
							'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar.png'
						),
						'default' => 'right-sidebar'							
					),
					'post-sidebar-left' => array(
						'title' => esc_html__('Default Post Sidebar Left', 'limoking'),
						'type' => 'combobox',
						'options' => $limoking_sidebar_controller->get_sidebar_array(),		
						'wrapper-class'=>'left-sidebar-wrapper both-sidebar-wrapper post-sidebar-template-wrapper',											
					),
					'post-sidebar-right' => array(
						'title' => esc_html__('Default Post Sidebar Right', 'limoking'),
						'type' => 'combobox',
						'options' => $limoking_sidebar_controller->get_sidebar_array(),
						'wrapper-class'=>'right-sidebar-wrapper both-sidebar-wrapper post-sidebar-template-wrapper',
					),										
				)
			);
			
			
			$array['general']['options']['blog-style'] = $post_option;
			return $array;
		}
	}		

	// add a post option to post page
	if( is_admin() ){ add_action('init', 'limoking_create_post_options'); }
	if( !function_exists('limoking_create_post_options') ){
	
		function limoking_create_post_options(){
			global $limoking_sidebar_controller;
			
			if( !class_exists('limoking_page_options') ) return;
			new limoking_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('post'),
					'meta_title' => esc_html__('Goodlayers Post Option', 'limoking'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => esc_html__('Page Layout', 'limoking'),
						'options' => array(
								'sidebar' => array(
									'title' => esc_html__('Sidebar Template' , 'limoking'),
									'type' => 'radioimage',
									'options' => array(
										'default-sidebar'=>get_template_directory_uri() . '/include/images/default-sidebar-2.png',
										'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar-2.png',
										'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar-2.png', 
										'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar-2.png',
										'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar-2.png'
									),
									'default' => 'default-sidebar'
								),	
								'left-sidebar' => array(
									'title' => esc_html__('Left Sidebar' , 'limoking'),
									'type' => 'combobox',
									'options' => $limoking_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper left-sidebar-wrapper both-sidebar-wrapper'
								),
								'right-sidebar' => array(
									'title' => esc_html__('Right Sidebar' , 'limoking'),
									'type' => 'combobox',
									'options' => $limoking_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper right-sidebar-wrapper both-sidebar-wrapper'
								),						
						)
					),
					
					'page-option' => array(
						'title' => esc_html__('Page Option', 'limoking'),
						'options' => array(
							'page-title' => array(
								'title' => esc_html__('Post Title' , 'limoking'),
								'type' => 'text',
								'description' => esc_html__('Leave this field blank to use the default title from admin panel > general > blog style section.', 'limoking')
							),
							'page-caption' => array(
								'title' => esc_html__('Post Caption' , 'limoking'),
								'type' => 'textarea'
							)						
						)
					),

				)
			);
			
		}
	}
	
	add_action('pre_post_update', 'limoking_save_post_meta_option');
	if( !function_exists('limoking_save_post_meta_option') ){
	function limoking_save_post_meta_option( $post_id ){
			if( get_post_type() == 'post' && isset($_POST['post-option']) ){
				$post_option = limoking_preventslashes(limoking_stripslashes($_POST['post-option']));
				$post_option = json_decode(limoking_decode_preventslashes($post_option), true);
			}
		}
	}
	
?>