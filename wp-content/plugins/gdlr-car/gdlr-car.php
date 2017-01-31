<?php
/*
Plugin Name: Goodlayers Car Post Type
Plugin URI: 
Description: A Custom Post Type Plugin To Use With Goodlayers Theme ( This plugin functionality might not working properly on another theme )
Version: 1.0.0
Author: Goodlayers
Author URI: http://www.goodlayers.com
License: 
*/
include_once( 'gdlr-car-contact.php');	
include_once( 'gdlr-car-item.php');	
include_once( 'gdlr-car-option.php');	

// action to loaded the plugin translation file
add_action('plugins_loaded', 'limoking_car_init');
if( !function_exists('limoking_car_init') ){
	function limoking_car_init() {
		load_plugin_textdomain( 'gdlr-car', false, dirname(plugin_basename( __FILE__ ))  . '/languages/' ); 
	}
}

// add action to create car post type
add_action( 'init', 'limoking_create_car' );
if( !function_exists('limoking_create_car') ){
	function limoking_create_car() {
		global $theme_option;
		
		if( !empty($theme_option['car-slug']) ){
			$car_slug = $theme_option['car-slug'];
			$car_category_slug = $theme_option['car-category-slug'];
			$car_tag_slug = $theme_option['car-tag-slug'];
		}else{
			$car_slug = 'car';
			$car_category_slug = 'car_category';
			$car_tag_slug = 'car_tag';
		}
		
		register_post_type( 'car',
			array(
				'labels' => array(
					'name'               => __('Cars', 'gdlr-car'),
					'singular_name'      => __('Car', 'gdlr-car'),
					'add_new'            => __('Add New', 'gdlr-car'),
					'add_new_item'       => __('Add New Car', 'gdlr-car'),
					'edit_item'          => __('Edit Car', 'gdlr-car'),
					'new_item'           => __('New Car', 'gdlr-car'),
					'all_items'          => __('All Cars', 'gdlr-car'),
					'view_item'          => __('View Car', 'gdlr-car'),
					'search_items'       => __('Search Car', 'gdlr-car'),
					'not_found'          => __('No cars found', 'gdlr-car'),
					'not_found_in_trash' => __('No cars found in Trash', 'gdlr-car'),
					'parent_item_colon'  => '',
					'menu_name'          => __('Cars', 'gdlr-car')
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $car_slug  ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
			)
		);
		
		// create car categories
		register_taxonomy(
			'car_category', array("car"), array(
				'hierarchical' => true,
				'show_admin_column' => true,
				'label' => __('Car Categories', 'gdlr-car'), 
				'singular_label' => __('Car Category', 'gdlr-car'), 
				'rewrite' => array( 'slug' => $car_category_slug  )));
		register_taxonomy_for_object_type('car_category', 'car');
		
		// create car tag
		register_taxonomy(
			'car_tag', array('car'), array(
				'hierarchical' => false, 
				'show_admin_column' => true,
				'label' => __('Car Tags', 'gdlr-car'), 
				'singular_label' => __('Car Tag', 'gdlr-car'),  
				'rewrite' => array( 'slug' => $car_tag_slug  )));
		register_taxonomy_for_object_type('car_tag', 'car');	

		add_filter('single_template', 'limoking_register_car_template');
	}
}

if( !function_exists('limoking_register_car_template') ){
	function limoking_register_car_template($single_template) {
		global $post;

		if ($post->post_type == 'car') {
			$single_template = dirname( __FILE__ ) . '/single-car.php';
		}
		return $single_template;	
	}
}

// add filter for adjacent car 
add_filter('get_previous_post_where', 'limoking_car_post_where', 10, 2);
add_filter('get_next_post_where', 'limoking_car_post_where', 10, 2);
if(!function_exists('limoking_car_post_where')){
	function limoking_car_post_where( $where, $in_same_cat ){ 
		global $post; 
		if ( $post->post_type == 'car' ){
			$current_taxonomy = 'car_category'; 
			$cat_array = wp_get_object_terms($post->ID, $current_taxonomy, array('fields' => 'ids')); 
			if($cat_array){ 
				$where .= " AND tt.taxonomy = '$current_taxonomy' AND tt.term_id IN (" . implode(',', $cat_array) . ")"; 
			} 
			}
		return $where; 
	} 	
}
	
add_filter('get_previous_post_join', 'get_car_post_join', 10, 2);
add_filter('get_next_post_join', 'get_car_post_join', 10, 2);	
if(!function_exists('get_car_post_join')){
	function get_car_post_join($join, $in_same_cat){ 
		global $post, $wpdb; 
		if ( $post->post_type == 'car' ){
			$current_taxonomy = 'car_category'; 
			if(wp_get_object_terms($post->ID, $current_taxonomy)){ 
				$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id"; 
			} 
		}
		return $join; 
	}
}

?>