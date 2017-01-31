<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the function that add and controls the font in the theme
	*	---------------------------------------------------------------------
	*/

	$limoking_font_family = array( 									
		'title' => esc_html__('Font Family', 'limoking'),
		'options' => array(
			
			'header-font-family' => array(
				'title' => esc_html__('Header Font', 'limoking'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => 'h1, h2, h3, h4, h5, h6, .limoking-title-font{ font-family: #gdlr#; }'
			),			
			'content-font-family' => array(
				'title' => esc_html__('Content Font', 'limoking'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => 'body, input, textarea, select, .limoking-content-font, .limoking-car-contact-form .wpcf7 input[type="submit"]{ font-family: #gdlr#; }'
			),			
			'info-font' => array(
				'title' => esc_html__('Info Font', 'limoking'),
				'type' => 'font-combobox',
				'default' => 'Crete Round',
				'data-type' => 'font',
				'selector' => '.limoking-info-font, .limoking-plain .about-us-caption, .limoking-normal .about-us-caption, ' . 
					'.limoking-button, input[type="button"], input[type="submit"]{ font-family: #gdlr#; }',
				'description' => esc_html__('For portfolio modern tag and plain about us caption', 'limoking')
			),			
			'testimonial-quote-font' => array(
				'title' => esc_html__('Testimonial Quote', 'limoking'),
				'type' => 'font-combobox',
				'default' => 'Crete Round',
				'data-type' => 'font',
				'selector' => '.limoking-testimonial-item.modern-style .testimonial-item-inner:before{ font-family: #gdlr#; }',
				'description' => esc_html__('For testimonial modern style.', 'limoking')
			),			
			'navigaiton-font-family' => array(
				'title' => esc_html__('Navigation Font', 'limoking'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => '.limoking-navigation{ font-family: #gdlr#; }'
			),			
			'slider-font-family' => array(
				'title' => esc_html__('Slider Font', 'limoking'),
				'type' => 'font-combobox',
				'default' => 'Arial, Helvetica, sans-serif',
				'data-type' => 'font',
				'selector' => '.limoking-slider-item{ font-family: #gdlr#; }'
			),
		)
	);	
	
	add_filter('limoking_admin_option', 'limoking_register_font_option');	
	if( !function_exists('limoking_register_font_option') ){
		function limoking_register_font_option( $array ){		
			if( empty($array['font-settings']['options']) ) return $array;
			
			global $limoking_font_family;
			
			$array['font-settings']['options']['font-family'] = $limoking_font_family;
			return $array;
		}
	}	
	
	// register the font script to embedding it when used.
	if( !function_exists('limoking_register_font_location') ){
		function limoking_register_font_location(){
			global $limoking_font_family, $limoking_font_controller;
			
			$font_location = array();
			foreach( $limoking_font_family['options'] as $font_slug => $font_settings ){
				array_push($font_location, $font_slug);
			}

			$limoking_font_controller->font_location = $font_location;
		}	
	}
	limoking_register_font_location();

?>