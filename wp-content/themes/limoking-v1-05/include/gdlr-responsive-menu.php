<?php
	/*	
	*	Goodlayers Menu Management File
	*	---------------------------------------------------------------------
	*	This file use to include a necessary script / function for the 
	* 	navigation area
	*	---------------------------------------------------------------------
	*/
	
	// add action to enqueue superfish menu
	add_filter('limoking_enqueue_scripts', 'limoking_register_dlmenu');
	if( !function_exists('limoking_register_dlmenu') ){
		function limoking_register_dlmenu($array){	
			$array['style']['dlmenu'] = get_template_directory_uri() . '/plugins/dl-menu/component.css';
			
			$array['script']['modernizr'] = get_template_directory_uri() . '/plugins/dl-menu/modernizr.custom.js';
			$array['script']['dlmenu'] = get_template_directory_uri() . '/plugins/dl-menu/jquery.dlmenu.js';

			return $array;
		}
	}
	
	// creating the class for outputing the custom navigation menu
	if( !class_exists('limoking_dlmenu_walker') ){
		
		// from wp-includes/nav-menu-template.php file
		class limoking_dlmenu_walker extends Walker_Nav_Menu{		

			function start_lvl( &$output, $depth = 0, $args = array() ) {
				$indent = str_repeat("\t", $depth);
				$output .= "\n$indent<ul class=\"dl-submenu\">\n";
			}

		}
		
	}

?>