<?php
	/*	
	*	Goodlayers Admin Panel
	*	---------------------------------------------------------------------
	*	This file create the class that help you create the controls page builder  
	*	option for custom theme
	*	---------------------------------------------------------------------
	*/	
	
	if( !class_exists('limoking_page_options') ){
		
		class limoking_page_options{

			public $setting;
			public $option;
		
			function __construct($setting = array(), $option = array()){
				
				$default_setting = array(
					'post_type' => array('page'),
					'meta_title' => esc_html__('Goodlayers Page Option', 'limoking'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'side',
					'priority' => 'high',
				);
				
				$this->setting = wp_parse_args($setting, $default_setting);
				$this->option = $option;
				
				// send the hook to create custom meta box
				add_action('add_meta_boxes', array(&$this, 'add_page_option_meta'));

				// add hook to save page options
				add_action('save_post', array(&$this, 'save_page_option'));
				add_action('pre_post_update', array(&$this, 'save_page_option'));
				
				// add filter for revision
				add_filter('_wp_post_revision_fields', array(&$this, 'add_preview_fields'));
			}			
			
			// load the necessary script for the page builder item
			function load_admin_script(){

				add_action('admin_enqueue_scripts', array(&$this, 'enqueue_wp_media') );
			
				// include the sidebar generator style
				wp_enqueue_style('wp-color-picker');
				wp_enqueue_style('gdlr-font-awesome', get_template_directory_uri() . '/plugins/font-awesome-new/css/font-awesome.min.css');	
				wp_enqueue_style('limoking-alert-box', get_template_directory_uri() . '/framework/stylesheet/gdlr-alert-box.css');	
				wp_enqueue_style('limoking-page-option', get_template_directory_uri() . '/framework/stylesheet/gdlr-page-option.css');
				wp_enqueue_style('limoking-admin-panel-html', get_template_directory_uri() . '/framework/stylesheet/gdlr-admin-panel-html.css');	
				wp_enqueue_style('limoking-edit-box', get_template_directory_uri() . '/framework/stylesheet/gdlr-edit-box.css');				
				wp_enqueue_style('limoking-date-picker', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');				

				// include the sidebar generator script
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_script('limoking-utility', get_template_directory_uri() . '/framework/javascript/gdlr-utility.js');	
				wp_enqueue_script('limoking-alert-box', get_template_directory_uri() . '/framework/javascript/gdlr-alert-box.js');
				wp_enqueue_script('limoking-page-option', get_template_directory_uri() . '/framework/javascript/gdlr-page-option.js');
				wp_enqueue_script('limoking-admin-panel-html', get_template_directory_uri() . '/framework/javascript/gdlr-admin-panel-html.js');
				wp_enqueue_script('limoking-edit-box', get_template_directory_uri() . '/framework/javascript/gdlr-edit-box.js');	
				wp_enqueue_script('limoking-slider-selection', get_template_directory_uri() . '/framework/javascript/gdlr-slider-selection.js');					
				wp_enqueue_script('jquery-ui-datepicker');					
			}			
			function enqueue_wp_media(){
				if(function_exists( 'wp_enqueue_media' )){
					wp_enqueue_media();
				}		
			}
			
			// create the page builder meta at the add_meta_boxes hook
			function add_page_option_meta(){
				global $post;
				
				if( in_array($post->post_type, $this->setting['post_type']) ){
					$this->load_admin_script();
				
					foreach( $this->setting['post_type'] as $post_type ){
						add_meta_box(
							$this->setting['meta_slug'],
							$this->setting['meta_title'],
							array(&$this, 'create_page_option_elements'),
							$post_type,
							$this->setting['position'],
							$this->setting['priority']
						);			
					}
				}
				
			}
		
			// start creating the page builder element
			function create_page_option_elements(){
				global $post;

				$option_value = limoking_decode_preventslashes(get_post_meta( $post->ID, $this->setting['option_name'], true ));
				if( !empty($option_value) ){
					$option_value = json_decode( $option_value, true );					
				}
	
				$option_generator = new limoking_admin_option_html();
				
				echo '<div class="limoking-page-option-wrapper position-' . $this->setting['position'] . '" >';
				
				foreach( $this->option as $option_section ){
					echo '<div class="limoking-page-option">';
					echo '<div class="limoking-page-option-title">' . $option_section['title'] . '</div>';
					echo '<div class="limoking-page-option-input-wrapper">';
					
					foreach ( $option_section['options'] as $option_slug => $option ){
						$option['slug'] = $option_slug;
						$option['name'] = '';
						if( !empty($option['custom_field']) ){
							$option['value'] = get_post_meta($post->ID, $option['custom_field'], true);
						}else if( !empty($option_value) && isset($option_value[$option_slug]) ){
							$option['value'] = $option_value[$option_slug];
						}
						
						$option_generator->generate_admin_option( $option );
					}
					
					echo '</div>'; // page-option-input-wrapper
					echo '</div>'; // page-option-title
					
					
				}
				echo '<textarea class="limoking-input-hidden" name="' . $this->setting['option_name'] . '"></textarea>';
				echo '</div>'; // limoking-page-option-wrapper
			}
			
			// save page option setting
			function save_page_option( $post_id ){
				if( empty($_POST[$this->setting['option_name']]) || empty($post_id) ) return;
				if( !in_array(get_post_type($post_id), $this->setting['post_type']) ) return; 
				
				if( isset($_POST[$this->setting['option_name']]) ){
					update_post_meta($post_id, $this->setting['option_name'], limoking_preventslashes($_POST[$this->setting['option_name']]));
				}
				
				$post_option = limoking_preventslashes(limoking_stripslashes($_POST['post-option']));
				$post_option = json_decode(limoking_decode_preventslashes($post_option), true);
				
				// for custom_field attribute
				foreach( $this->option as $option_section ){
					foreach ( $option_section['options'] as $option_slug => $option ){
						if( !empty($option['custom_field']) ){
							update_post_meta($post_id, $option['custom_field'], $post_option[$option_slug]);
						}
					}
				}
				
				// for custom meta filter
				$custom_meta = apply_filters('limoking_custom_page_option_meta', array(), $post_option);
				if( !empty($custom_meta) ){
					foreach( $custom_meta as $meta ){
						if( !empty($meta['key']) && !empty($meta['value']) ){
							update_post_meta($post_id, $meta['key'], $meta['value']);
						}
					}
				}
			}
	
			// for preview saving
			function add_preview_fields($fields){
			   $fields[$this->setting['option_name']] = $this->setting['option_name'];
			   return $fields;
			}		
			
		}
		
		
	}

?>