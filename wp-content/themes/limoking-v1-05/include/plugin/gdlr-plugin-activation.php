<?php
require_once(get_template_directory() . '/include/plugin/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'limoking_register_required_plugins' );
if( !function_exists('limoking_register_required_plugins') ){
	function limoking_register_required_plugins(){
		$plugins = array(
			array(
				'name'     				=> 'masterslider',
				'slug'     				=> 'masterslider', 
				'source'   				=> get_template_directory() . '/include/plugin/plugins/masterslider.zip',
				'version'               => '2.25.4',
				'required' 				=> true,
				'force_activation' 		=> false,
				'force_deactivation' 	=> true, 
			),
			array(
				'name'     				=> 'Goodlayers Importer',
				'slug'     				=> 'goodlayers-importer', 
				'source'   				=> get_template_directory() . '/include/plugin/plugins/goodlayers-importer.zip',
				'version'               => '1.0.0',
				'required' 				=> true,
				'force_activation' 		=> false,
				'force_deactivation' 	=> false, 
			),		
			array(
				'name'     				=> 'Goodlayers Function',
				'slug'     				=> 'gdlr-function', 
				'source'   				=> get_template_directory() . '/include/plugin/plugins/gdlr-function.zip',
				'required' 				=> true,
				'force_activation' 		=> false,
				'force_deactivation' 	=> false, 
			),
			array(
				'name'     				=> 'Goodlayers Portfolio',
				'slug'     				=> 'gdlr-portfolio', 
				'version'               => '1.0.0',
				'source'   				=> get_template_directory() . '/include/plugin/plugins/gdlr-portfolio.zip',
				'required' 				=> true,
				'force_activation' 		=> false,
				'force_deactivation' 	=> false, 
			),
			array(
				'name'     				=> 'Goodlayers Car',
				'slug'     				=> 'gdlr-car', 
				'version'               => '1.0.0',
				'source'   				=> get_template_directory() . '/include/plugin/plugins/gdlr-car.zip',
				'required' 				=> true,
				'force_activation' 		=> false,
				'force_deactivation' 	=> false, 
			),
			
			array('name' => 'Contact Form 7', 'slug' => 'contact-form-7', 'required' => true),
			array('name' => 'Wp Google Maps', 'slug' => 'wp-google-map-plugin', 'required' => false),

		);

		$config = array(
			'id'           => 'limoking',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.		
		);

		tgmpa( $plugins, $config );
	}
}
?>