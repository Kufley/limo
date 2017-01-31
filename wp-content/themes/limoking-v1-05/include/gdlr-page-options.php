<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the admin option setting 
	*	---------------------------------------------------------------------
	*/

	// create the page option
	add_action('init', 'limoking_create_page_options');
	if( !function_exists('limoking_create_page_options') ){
	
		function limoking_create_page_options(){
			global $limoking_sidebar_controller;
		
			new limoking_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('page'),
					'meta_title' => esc_html__('Goodlayers Page Option', 'limoking'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'side',
					'priority' => 'core',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => esc_html__('Page Layout', 'limoking'),
						'options' => array(
								'sidebar' => array(
									'type' => 'radioimage',
									'options' => array(
										'no-sidebar'=>get_template_directory_uri() . '/include/images/no-sidebar-2.png',
										'both-sidebar'=>get_template_directory_uri() . '/include/images/both-sidebar-2.png', 
										'right-sidebar'=>get_template_directory_uri() . '/include/images/right-sidebar-2.png',
										'left-sidebar'=>get_template_directory_uri() . '/include/images/left-sidebar-2.png'
									),
									'default'=>'no-sidebar'
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
								'page-style' => array(
									'title' => esc_html__('Page Style' , 'limoking'),
									'type' => 'combobox',
									'options' => array(
										'normal'=> esc_html__('Normal', 'limoking'),
										'no-header'=> esc_html__('No Header', 'limoking'),
										'no-footer'=> esc_html__('No Footer', 'limoking'),
										'no-header-footer'=> esc_html__('No Header / No Footer', 'limoking'),
									)
								),
						)
					),
					
					'page-option' => array(
						'title' => esc_html__('Page Option', 'limoking'),
						'options' => array(
							'show-title' => array(
								'title' => esc_html__('Show Title' , 'limoking'),
								'type' => 'checkbox',
								'default' => 'enable',
							),						
							'page-caption' => array(
								'title' => esc_html__('Page Caption' , 'limoking'),
								'type' => 'textarea'
							),		
							'show-content' => array(
								'title' => esc_html__('Show Content (From Default Editor)' , 'limoking'),
								'type' => 'checkbox',
								'default' => 'enable',
							),								
							'header-background' => array(
								'title' => esc_html__('Header Background Image' , 'limoking'),
								'button' => esc_html__('Upload', 'limoking'),
								'type' => 'upload',
							)						
						)
					),

				)
			);
			
		}
	}

?>