<?php
	/*	
	*	Goodlayers Admin Panel
	*	---------------------------------------------------------------------
	*	This file create the class that help you create the controls admin  
	*	option for custom theme
	*	---------------------------------------------------------------------
	*/	
	
	if( !class_exists('limoking_admin_option') ){
		
		class limoking_admin_option{
			
			public $setting;
			public $option;		
			public $value;
			
			function __construct($setting = array(), $option = array(), $value = array()){
				
				$default_setting = array(
					'page_title' => esc_html__('Custom Option', 'limoking'),
					'menu_title' => esc_html__('Custom Menu', 'limoking'),
					'menu_slug' => 'custom-menu',
					'save_option' => 'limoking_admin_option',
					'role' => 'edit_theme_options',
					'icon_url' => '',
					'position' => 82
				);
				
				$this->setting = wp_parse_args($setting, $default_setting);
				$this->option = $option;
				$this->value = $value;

				new limoking_theme_customizer($option);
				
				// send the hook to create the admin menu
				add_action('admin_menu', array(&$this, 'register_main_admin_option'));
				
				// set the hook for saving the admin menu
				add_action('wp_ajax_limoking_save_admin_panel_' . $this->setting['menu_slug'], array(&$this, 'limoking_save_admin_panel'));
			}
			
			// create the admin menu
			function register_main_admin_option(){
				
				// add the hook to create admin option
				$page = add_theme_page($this->setting['page_title'], $this->setting['menu_title'], 
					$this->setting['role'], $this->setting['menu_slug'], 
					array(&$this, 'create_admin_option')); 

				// include the script to admin option
				add_action('admin_print_styles-' . $page, array(&$this, 'register_admin_option_style'));	
				add_action('admin_print_scripts-' . $page, array(&$this, 'register_admin_option_script'));			
			}
			
			// include script and style when you're on admin option
			function register_admin_option_style(){
				wp_enqueue_style('wp-color-picker');
				wp_enqueue_style('limoking-alert-box', get_template_directory_uri() . '/framework/stylesheet/gdlr-alert-box.css');						
				wp_enqueue_style('limoking-admin-panel', get_template_directory_uri() . '/framework/stylesheet/gdlr-admin-panel.css');						
				wp_enqueue_style('limoking-admin-panel-html', get_template_directory_uri() . '/framework/stylesheet/gdlr-admin-panel-html.css');
				wp_enqueue_style('limoking-date-picker', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');				
			}
			function register_admin_option_script(){
				if(function_exists( 'wp_enqueue_media' )){
					wp_enqueue_media();
				}		
				
				wp_enqueue_script('jquery-ui-datepicker');	
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-slider');
				wp_enqueue_script('wp-color-picker');			
				wp_enqueue_script('limoking-alert-box', get_template_directory_uri() . '/framework/javascript/gdlr-alert-box.js');
				wp_enqueue_script('limoking-admin-panel', get_template_directory_uri() . '/framework/javascript/gdlr-admin-panel.js');
				wp_enqueue_script('limoking-admin-panel-html', get_template_directory_uri() . '/framework/javascript/gdlr-admin-panel-html.js');
			}
			
			// saving admin option
			function limoking_save_admin_panel(){
				if( !check_ajax_referer(THEME_SHORT_NAME . '-create-nonce', 'security', false) ){
					die(json_encode(array(
						'status'=>'failed', 
						'message'=> '<span class="head">' . esc_html__('Invalid Nonce', 'limoking') . '</span> ' .
							esc_html__('Please refresh the page and try this again.' ,'limoking')
					)));
				}
				
				if( isset($_POST['option']) ){		
					parse_str(limoking_stripslashes($_POST['option']), $option ); 
					$option = limoking_stripslashes($option);
					
					$old_option = get_option($this->setting['save_option']);
					  
					if($old_option == $option || update_option($this->setting['save_option'], $option)){
						$ret = array(
							'status'=> 'success', 
							'message'=> '<span class="head">' . esc_html__('Save Options Complete' ,'limoking') . '</span> '
						);		
					}else{
						$ret = array(
							'status'=> 'failed', 
							'message'=> '<span class="head">' . esc_html__('Save Options Failed', 'limoking') . '</span> ' .
							esc_html__('Please refresh the page and try this again.' ,'limoking')
						);					
					}
				}else{
					$ret = array(
						'status'=>'failed', 
						'message'=> '<span class="head">' . esc_html__('Cannot Retrieve Options', 'limoking') . '</span> ' .
							esc_html__('Please refresh the page and try this again.' ,'limoking')
					);	
				}
				
				do_action('limoking_save_' . $this->setting['menu_slug'], $this->option);
				
				die(json_encode($ret));
			}
			
			// creating the content of the admin option
			function create_admin_option(){
				echo '<div class="limoking-admin-panel-wrapper">';
				
				echo '<form action="#" method="POST" id="limoking-admin-form" data-action="limoking_save_admin_panel_' . $this->setting['menu_slug'] . '" ';
				echo 'data-ajax="' . esc_url(AJAX_URL) . '" ';
				echo 'data-security="' . wp_create_nonce(THEME_SHORT_NAME . '-create-nonce') . '" >';
				
				// print navigation section
				$this->print_admin_nav();
				
				// print content section
				$this->print_admin_content();
				
				echo '<div class="clear"></div>';
				echo '</form>';	

				echo '</div>'; // limoking-admin-panel-wrapper
			}	

			function print_admin_nav(){
				
				// admin navigation
				echo '<div class="limoking-admin-nav-wrapper" id="limoking-admin-nav" >';
				echo '<div class="limoking-admin-head">';
				echo '<img src="' . get_template_directory_uri() . '/framework/images/admin-panel/admin-logo.png" alt="admin logo" />';
				echo '<div class="limoking-admin-head-gimmick"></div>';
				echo '</div>';
				
				$is_first = 'active';
				
				echo '<ul class="admin-menu" >';
				foreach( $this->option as $menu_slug => $menu_settings ){
					echo '<li class="' . $menu_slug . '-wrapper admin-menu-list">';
					
					echo '<div class="menu-title">';
					echo '<img src="' . $menu_settings['icon'] . '" alt="' . $menu_settings['title'] . '" />';
					echo '<span>' . $menu_settings['title'] . '</span>';
					echo '<div class="menu-title-gimmick"></div>';
					echo '</div>';
					
					echo '<ul class="admin-sub-menu">';
					foreach( $menu_settings['options'] as $sub_menu_slug => $sub_menu_settings ){
						if( !empty($sub_menu_settings) ){
							echo '<li class="' . $sub_menu_slug . '-wrapper ' . $is_first . ' admin-sub-menu-list" data-id="' . $sub_menu_slug . '" >';
							echo '<div class="sub-menu-title">';
							echo limoking_escape_content($sub_menu_settings['title']);
							echo '</div>';
							echo '</li>';
							
							$is_first = '';
						}
					}
					echo '</ul>';
					
					echo '</li>';
				}
				echo '</ul>';
				
				echo '</div>'; // limoking-admin-nav-wrapper				
			}
			
			function print_admin_content(){
			
				$option_generator = new limoking_admin_option_html();

				// admin content
				echo '<div class="limoking-admin-content-wrapper" id="limoking-admin-content">';
				
				echo '<div class="limoking-admin-head">';
				echo '<div class="limoking-save-button">';
				echo '<img class="now-loading" src="' . get_template_directory_uri() . '/framework/images/admin-panel/loading.gif" alt="loading" />';				
				echo '<input value="' . esc_html__('Save Changes', 'limoking') . '" type="submit" class="gdl-button" />';
				echo '</div>'; 
				
				echo '<div class="limoking-admin-head-gimmick"></div>';
				
				echo '<div class="clear"></div>';
				echo '</div>'; // limoking-admin-head
				
				echo '<div class="limoking-content-group">';
				foreach( $this->option as $menu_slug => $menu_settings ){
					foreach( $menu_settings['options'] as $sub_menu_slug => $sub_menu_settings ){
						if( !empty($sub_menu_settings) ){
							echo '<div class="limoking-content-section" id="' . $sub_menu_slug . '" >';
							foreach( $sub_menu_settings['options'] as $option_slug => $option_settings ){
								$option_settings['slug'] = $option_slug;
								$option_settings['name'] = $option_slug;
								if( isset($this->value[$option_slug]) ){
									$option_settings['value'] = $this->value[$option_slug];
								}
								
								$option_generator->generate_admin_option($option_settings);
							}
							echo '</div>'; // limoking-content-section
						}
					}
				}								
				echo '</div>'; // limoking-content-group

				echo '<div class="limoking-admin-footer">';
				echo '<div class="limoking-save-button">';
				echo '<img class="now-loading" src="' . get_template_directory_uri() . '/framework/images/admin-panel/loading.gif" alt="loading" />';
				echo '<input value="' . esc_html__('Save Changes', 'limoking') . '" type="submit" class="gdl-button" />';
				echo '</div>';
				
				echo '<div class="clear"></div>';
				echo '</div>'; // limoking-admin-footer
				
				echo '</div>'; // limoking-admin-content-wrapper
			
			}
			
		}
		
	}	

?>