<?php
/**
 * Plugin Name: Goodlayers Recent Post
 * Plugin URI: http://goodlayers.com/
 * Description: A widget that show recent posts( Specified by category ).
 * Version: 1.0
 * Author: Goodlayers
 * Author URI: http://www.goodlayers.com
 *
 */

add_action( 'widgets_init', 'limoking_port_slider_widget' );
if( !function_exists('limoking_port_slider_widget') ){
	function limoking_port_slider_widget() {
		register_widget( 'Goodlayers_Port_Slider' );
	}
}

if( !class_exists('Goodlayers_Port_Slider') ){
	class Goodlayers_Port_Slider extends WP_Widget{

		// Initialize the widget
		function __construct() {
			parent::__construct(
				'gdlr-port-slider-widget', 
				esc_html__('Goodlayers Port Slider Widget','limoking'), 
				array('description' => esc_html__('A widget that show lastest portfolios in slider format', 'limoking')));  
		}

		// Output of the widget
		function widget( $args, $instance ) {
			global $theme_option;	
				
			$title = apply_filters( 'widget_title', $instance['title'] );
			$category = $instance['category'];
			$num_fetch = $instance['num_fetch'];
			$thumbnail_size = $instance['thumbnail_size'];
			
			// Opening of widget
			echo limoking_escape_content($args['before_widget']);
			
			// Open of title tag
			if( !empty($title) ){ 
				echo limoking_escape_content($args['before_title'] . $title . $args['after_title']); 
			}
				
			// Widget Content
			$current_post = array(get_the_ID());		
			$query_args = array('post_type' => 'portfolio', 'suppress_filters' => false);
			$query_args['posts_per_page'] = $num_fetch;
			$query_args['orderby'] = 'post_date';
			$query_args['order'] = 'desc';
			$query_args['paged'] = 1;
			$query_args['portfolio_category'] = $category;
			$query_args['ignore_sticky_posts'] = 1;
			$query_args['post__not_in'] = array(get_the_ID());
			$query = new WP_Query( $query_args );
			
			if($query->have_posts()){
				echo '<div class="limoking-post-slider-widget">';
				echo '<div class="flexslider" >';
				echo '<ul class="slides" >';
				while($query->have_posts()){ $query->the_post();
					$image_id = get_post_thumbnail_id();
					
					if( !empty($image_id) ){
						echo '<li>';
						echo '<a href="' . get_permalink() . '" >';
						echo limoking_get_image($image_id, $thumbnail_size);
						echo '<div class="limoking-caption-wrapper post-slider">';
						echo '<div class="limoking-caption-title">';
						echo get_the_title();
						echo '</div>'; // limoking-caption-title
						echo '</div>'; // limoking-caption-wrapper
						echo '</a>';
						echo '</li>';
					}
				}
				echo '</ul>';
				echo '</div>'; 
				echo '</div>'; // limoking-post-slider-widget
			}
			wp_reset_postdata();
					
			// Closing of widget
			echo limoking_escape_content($args['after_widget']);	
		}

		// Widget Form
		function form( $instance ) {
			$title = isset($instance['title'])? $instance['title']: '';
			$category = isset($instance['category'])? $instance['category']: '';
			$num_fetch = isset($instance['num_fetch'])? $instance['num_fetch']: 3;
			$thumbnail_size = isset($instance['thumbnail_size'])? $instance['thumbnail_size']: '';
			?>

			<!-- Text Input -->
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title :', 'limoking'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo limoking_escape_content($title); ?>" />
			</p>		

			<!-- Post Category -->
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php esc_html_e('Category :', 'limoking'); ?></label>		
				<select class="widefat" name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>">
				<option value="" <?php if(empty($category)) echo ' selected '; ?>><?php esc_html_e('All', 'limoking') ?></option>
				<?php 	
				$category_list = limoking_get_term_list('portfolio_category'); 
				foreach($category_list as $cat_slug => $cat_name){ ?>
					<option value="<?php echo limoking_escape_content($cat_slug); ?>" <?php if ($category == $cat_slug) echo ' selected '; ?>><?php echo limoking_escape_content($cat_name); ?></option>				
				<?php } ?>	
				</select> 
			</p>
				
			<!-- Show Num --> 
			<p>
				<label for="<?php echo $this->get_field_id('num_fetch'); ?>"><?php esc_html_e('Num Fetch :', 'limoking'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('num_fetch'); ?>" name="<?php echo $this->get_field_name('num_fetch'); ?>" type="text" value="<?php echo limoking_escape_content($num_fetch); ?>" />
			</p>
			
			<!-- Thumbnail Size -->
			<p>
				<label for="<?php echo $this->get_field_id('thumbnail_size'); ?>"><?php esc_html_e('Thumbnail Size :', 'limoking'); ?></label>		
				<select class="widefat" name="<?php echo $this->get_field_name('thumbnail_size'); ?>" id="<?php echo $this->get_field_id('thumbnail_size'); ?>">
				<?php 	
				$thumbnails = limoking_get_thumbnail_list();
				foreach($thumbnails as $th_slug => $th_name){ ?>
					<option value="<?php echo limoking_escape_content($th_slug); ?>" <?php if ($thumbnail_size == $th_slug) echo ' selected '; ?>><?php echo limoking_escape_content($th_name); ?></option>				
				<?php } ?>	
				</select> 
			</p>			

		<?php
		}
		
		// Update the widget
		function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = (empty($new_instance['title']))? '': strip_tags($new_instance['title']);
			$instance['category'] = (empty($new_instance['category']))? '': strip_tags($new_instance['category']);
			$instance['num_fetch'] = (empty($new_instance['num_fetch']))? '': strip_tags($new_instance['num_fetch']);
			$instance['thumbnail_size'] = (empty($new_instance['thumbnail_size']))? '': strip_tags($new_instance['thumbnail_size']);

			return $instance;
		}	
	}
}
?>