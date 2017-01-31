<?php
/**
 * Plugin Name: Goodlayers Video
 * Plugin URI: http://goodlayers.com/
 * Description: A widget that show video.
 * Version: 1.0
 * Author: Goodlayers
 * Author URI: http://www.goodlayers.com
 *
 */

add_action( 'widgets_init', 'limoking_video_widget' );
if( !function_exists('limoking_video_widget') ){
	function limoking_video_widget() {
		register_widget( 'Goodlayers_Video_Widget' );
	}
}

if( !class_exists('Goodlayers_Video_Widget') ){
	class Goodlayers_Video_Widget extends WP_Widget{

		// Initialize the widget
		function __construct() {
			parent::__construct(
				'gdlr-video-widget', 
				esc_html__('Goodlayers Video Widget','limoking'), 
				array('description' => esc_html__('A widget that show video specify by url', 'limoking')));  
		}

		// Output of the widget
		function widget( $args, $instance ) {
			global $theme_option;	
				
			$title = apply_filters( 'widget_title', $instance['title'] );
			$url = $instance['url'];
			
			// Opening of widget
			echo limoking_escape_content($args['before_widget']);
			
			// Open of title tag
			if( !empty($title) ){ 
				echo limoking_escape_content($args['before_title'] . $title . $args['after_title']); 
			}
				
			// Widget Content
			echo '<div class="limoking-video-widget">';
			echo limoking_get_video($url, 300);
			echo '</div>';
					
			// Closing of widget
			echo limoking_escape_content($args['after_widget']);	
		}

		// Widget Form
		function form( $instance ) {
			$title = isset($instance['title'])? $instance['title']: '';
			$url = isset($instance['url'])? $instance['url']: '';
			
			?>

			<!-- Text Input -->
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title :', 'limoking'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo limoking_escape_content($title); ?>" />
			</p>		
			
			<!-- URL --> 
			<p>
				<label for="<?php echo $this->get_field_id('url'); ?>"><?php esc_html_e('Video URL :', 'limoking'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo limoking_escape_content($url); ?>" />
			</p>

		<?php
		}
		
		// Update the widget
		function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = (empty($new_instance['title']))? '': strip_tags($new_instance['title']);
			$instance['url'] = (empty($new_instance['url']))? '': strip_tags($new_instance['url']);
			
			return $instance;
		}	
	}
}
?>