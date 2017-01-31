<?php
	/*	
	*	Goodlayers Sidebar Generator
	*	---------------------------------------------------------------------
	*	This file create the class that help you to controls the sidebar 
	*	at the appearance > widget area
	*	---------------------------------------------------------------------
	*/
	
	if( !class_exists('limoking_sidebar_generator') ){
		
		class limoking_sidebar_generator{
			
			var $option_name = 'gdlr_sidebar_name';
			
			var $sidebars = array();
			var $footer_widgets = array();
			
			function __construct(){
				global $pagenow;
				if( is_admin() && $pagenow == 'customize.php' ) return;			
			
				$this->footer_widgets = array(
					array( 'name'=>'Footer 1', 'description'=>esc_html__('Footer Column 1', 'limoking') ), 
					array( 'name'=>'Footer 2', 'description'=>esc_html__('Footer Column 2', 'limoking') ), 
					array( 'name'=>'Footer 3', 'description'=>esc_html__('Footer Column 3', 'limoking') ), 
					array( 'name'=>'Footer 4', 'description'=>esc_html__('Footer Column 4', 'limoking') )
				);
				
				$this->sidebars = get_option($this->option_name, array());
				if( !is_array($this->sidebars) ){ $this->sidebars = array(); }
				
				$this->register_sidebar();
				
				// add the script when opening the admin widget section
				add_action('load-widgets.php', array(&$this, 'load_admin_script') );
				add_action('load-widgets.php', array(&$this, 'load_admin_script') );
				
				// set the hook for adding/removing sidebar
				add_action('wp_ajax_limoking_add_sidebar', array(&$this, 'limoking_add_sidebar'));	
				add_action('wp_ajax_limoking_remove_sidebar', array(&$this, 'limoking_remove_sidebar'));	
								
			}
			
			// register sidebar to use in widget area
			function register_sidebar(){
				$sidebar_id = 1;
				
				$args = array(
					'before_widget' => '<div id="%1$s" class="widget %2$s limoking-item limoking-widget">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3 class="limoking-widget-title">',
					'after_title'   => '</h3><div class="clear"></div>' );		

				// sidebar for footer section
				$footer_args = apply_filters('limoking_footer_widget_args', array());
				$footer_args = wp_parse_args($footer_args, $args);
				foreach ( $this->footer_widgets as $widget ){
					if( !is_array($widget) ){
						$footer_args['name'] = $widget;
						$footer_args['description'] = esc_html__('Custom widget area', 'limoking');
					}else{
						$footer_args['name'] = $widget['name'];
						$footer_args['description'] = $widget['description'];
					}
					
					$footer_args['id'] = 'sidebar-' . $sidebar_id;
					$sidebar_id++;
					register_sidebar($footer_args);
				}
				
				// sidebar for content section
				$sidebar_args = apply_filters('limoking_sidebar_widget_args', array());
				$sidebar_args = wp_parse_args($sidebar_args, $args);				
				$sidebar_args['class'] = 'limoking-dynamic';
				foreach ( $this->sidebars as $sidebar ){
					$sidebar_args['name'] = $sidebar;
					$sidebar_args['description'] = esc_html__('Custom widget area', 'limoking');
					
					$sidebar_args['id'] = 'sidebar-' . $sidebar_id;
					$sidebar_id++;
					register_sidebar($sidebar_args);
				}
				
			}
			
			// load the necessary script for the sidebar creator item
			function load_admin_script(){
				
				// include the sidebar generator style
				wp_enqueue_style('limoking-alert-box', get_template_directory_uri() . '/framework/stylesheet/gdlr-alert-box.css');
				wp_enqueue_style('limoking-sidebar-generator', get_template_directory_uri() . '/framework/stylesheet/gdlr-sidebar-generator.css');
			
				// include the sidebar generator script
				wp_enqueue_script('limoking-alert-box', get_template_directory_uri() . '/framework/javascript/gdlr-alert-box.js');
				wp_enqueue_script('limoking-sidebar-generator', get_template_directory_uri() . '/framework/javascript/gdlr-sidebar-generator.js');
				
				// execute the sidebar generator script
				add_action('admin_print_scripts', array(&$this, 'limoking_create_sidebar_script') );
				
			}
			
			// add the necessary variable for ajax purpose
			function limoking_create_sidebar_script(){
?>
<script type="text/javascript"> 
var limoking_nonce = "<?php echo wp_create_nonce(THEME_SHORT_NAME . '-create-nonce'); ?>";
var limoking_title = "<?php esc_html_e('Create New Sidebar' ,'limoking'); ?>";
var limoking_ajax = "<?php echo esc_url(AJAX_URL); ?>";
</script>		
<?php
			}
			
			// add new sidebar ajax module
			function limoking_add_sidebar(){
				if( !check_ajax_referer(THEME_SHORT_NAME . '-create-nonce', 'security', false) ){
					die(json_encode(array(
						'status'=>'failed', 
						'message'=> '<span class="head">' . esc_html__('Invalid Nonce', 'limoking') . '</span> ' .
							esc_html__('Please refresh the page and try this again.' ,'limoking')
					)));
				}
				
				if( isset($_POST['sidebar_name']) ){		
					
					if( !in_array(trim($_POST['sidebar_name']), $this->sidebars) ){
						
						array_push($this->sidebars, limoking_stripslashes(trim($_POST['sidebar_name'])));
						
						if( update_option($this->option_name, $this->sidebars) ){
							$ret = array(
								'status'=> 'success'
							);		
						}else{
							$ret = array(
								'status'=> 'failed', 
								'message'=> '<span class="head">' . esc_html__('Save Sidebar Failed', 'limoking') . '</span> ' .
								esc_html__('Please try creating the sidebar again with different name.' ,'limoking')
							);						
						}
	
					}else{
						$ret = array(
							'status'=> 'failed', 
							'message'=> '<span class="head">' . esc_html__('Duplicated Sidebar Name', 'limoking') . '</span> ' .
							esc_html__('Please try creating the sidebar again with different name.' ,'limoking')
						);					
					}
				}else{
					$ret = array(
						'status'=>'failed', 
						'message'=> '<span class="head">' . esc_html__('Cannot Retrieve Sidebar Name', 'limoking') . '</span> ' .
							esc_html__('Please refresh the page and try this again.' ,'limoking')
					);	
				}
				
				die(json_encode($ret));
			}	

			// add new sidebar ajax module
			function limoking_remove_sidebar(){
				if( !check_ajax_referer(THEME_SHORT_NAME . '-create-nonce', 'security', false) ){
					die(json_encode(array(
						'status'=>'failed', 
						'message'=> '<span class="head">' . esc_html__('Invalid Nonce', 'limoking') . '</span> ' .
							esc_html__('Please refresh the page and try this again.' ,'limoking')
					)));
				}
				
				if( isset($_POST['sidebar_name']) ){		
				
					$current_sidebar = limoking_stripslashes(trim(strip_tags($_POST['sidebar_name'])));
					
					$key = array_search($current_sidebar, $this->sidebars);
					unset($this->sidebars[$key]);
					
					if( update_option($this->option_name, $this->sidebars) ){
						$ret = array(
							'status'=> 'success'
						);		
					}else{
						$ret = array(
							'status'=> 'failed', 
							'message'=> '<span class="head">' . esc_html__('Save Failed', 'limoking') . '</span> ' .
							esc_html__('Please try again.' ,'limoking')
						);						
					}
				}else{
					$ret = array(
						'status'=>'failed', 
						'message'=> '<span class="head">' . esc_html__('Cannot Retrieve Sidebar Name', 'limoking') . '</span> ' .
							esc_html__('Please try again.' ,'limoking')
					);	
				}
				
				die(json_encode($ret));
			}

			// get all sidebar array
			function get_sidebar_array(){
				$ret = array();

				foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
					if( !in_array( $sidebar['name'], $this->footer_widgets ) ){
						$ret[$sidebar['name']] = $sidebar['name'];
					}
				}
				return $ret;
			}

		}
		
	}
	

?>