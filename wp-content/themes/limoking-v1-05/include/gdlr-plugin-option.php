<?php
	/*	
	*	Goodlayers Framework File
	*	---------------------------------------------------------------------
	*	This file contains the code to register the goodlayers plugin option
	*	to admin area
	*	---------------------------------------------------------------------
	*/

	// add an admin option to portfolio
	add_filter('limoking_admin_option', 'limoking_register_portfolio_admin_option');
	if( !function_exists('limoking_register_portfolio_admin_option') ){
		
		function limoking_register_portfolio_admin_option( $array ){		
			if( empty($array['general']['options']) ) return $array;
			
			$portfolio_option = array( 									
				'title' => esc_html__('Portfolio Style', 'limoking'),
				'options' => array(
					'portfolio-slug' => array(
						'title' => esc_html__('Portfolio Slug ( Permalink )', 'limoking'),
						'type' => 'text',
						'default' => 'portfolio',
						'description' => esc_html__('Only <strong>a-z (lower case), hyphen and underscore</strong> is allowed here <br><br>', 'limoking') .
							esc_html__('After changing, you have to set the permalink at the setting > permalink to default (to reset the permalink rules) as well.', 'limoking')
					),
					'portfolio-category-slug' => array(
						'title' => esc_html__('Portfolio Category Slug ( Permalink )', 'limoking'),
						'type' => 'text',
						'default' => 'portfolio_category',
					),
					'portfolio-tag-slug' => array(
						'title' => esc_html__('Portfolio Tag Slug ( Permalink )', 'limoking'),
						'type' => 'text',
						'default' => 'portfolio_tag',
					),					
					'portfolio-comment' => array(
						'title' => esc_html__('Enable Comment On Portfolio', 'limoking'),
						'type' => 'checkbox',
						'default' => 'disable'
					),	
					'portfolio-related' => array(
						'title' => esc_html__('Enable Related Portfolio', 'limoking'),
						'type' => 'checkbox',
						'default' => 'enable'
					),					
					'portfolio-page-style' => array(
						'title' => esc_html__('Portfolio Page Style', 'limoking'),
						'type'=> 'combobox',
						'options'=> array(
							'style1'=> esc_html__('Portfolio Style 1', 'limoking'),
							'style2'=> esc_html__('Portfolio Style 2', 'limoking'),
							'blog-style'=> esc_html__('Blog Style', 'limoking'),
						)
					),					
					'portfolio-thumbnail-size' => array(
						'title' => esc_html__('Single Portfolio Thumbnail Size', 'limoking'),
						'type'=> 'combobox',
						'options'=> limoking_get_thumbnail_list(),
						'default'=> 'post-thumbnail-size'
					),	
					'related-portfolio-style' => array(
						'title' => esc_html__('Related Portfolio Style', 'limoking'),
						'type'=> 'combobox',
						'options'=> array(
							'classic-portfolio'=> esc_html__('Portfolio Classic Style', 'limoking'),
							'modern-portfolio'=> esc_html__('Portfolio Modern Style', 'limoking')
						)
					),
					'related-portfolio-size' => array(
						'title' => esc_html__('Related Portfolio Size', 'limoking'),
						'type'=> 'combobox',
						'options'=> array(
							'2'=> esc_html__('1/2', 'limoking'),
							'3'=> esc_html__('1/3', 'limoking'),
							'4'=> esc_html__('1/4', 'limoking'),
							'5'=> esc_html__('1/5', 'limoking')
						),
						'default'=>'4'
					),					
					'related-portfolio-num-fetch' => array(
						'title' => esc_html__('Related Portfolio Num Fetch', 'limoking'),
						'type'=> 'text',
						'default'=> '4'
					),					
					'related-portfolio-num-excerpt' => array(
						'title' => esc_html__('Related Portfolio Num Excerpt', 'limoking'),
						'type'=> 'text',
						'default'=> '25'
					),
					'related-portfolio-thumbnail-size' => array(
						'title' => esc_html__('Related Portfolio Thumbnail Size', 'limoking'),
						'type'=> 'combobox',
						'options'=> limoking_get_thumbnail_list(),
						'default'=> 'small-grid-size'
					),					
				)
			);
			
			$array['general']['options']['portfolio-style'] = $portfolio_option;
			return $array;
		}
		
	}		

?>