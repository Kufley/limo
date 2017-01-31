<?php
/*
 * The template for displaying a header title section
 */
	global $theme_option, $limoking_post_option, $header_style;
	$header_background = '';
	if( !empty($limoking_post_option['header-background']) ){
		if( is_numeric($limoking_post_option['header-background']) ){
			$image_src = wp_get_attachment_image_src($limoking_post_option['header-background'], 'full');	
			$header_background = $image_src[0];
		}else{
			$header_background = $limoking_post_option['header-background'];
		}
	}
?>

	<?php if( is_front_page() && !is_page() ){ ?>
		<div class="limoking-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" >
			<div class="limoking-page-title-overlay"></div>
			<div class="limoking-page-title-container container" >
				<h1 class="limoking-page-title"><?php esc_html_e('Home', 'limoking') ?></h1>
			</div>	
		</div>	
	<?php }else if( is_page() && (empty($limoking_post_option['show-title']) || $limoking_post_option['show-title'] != 'disable') ){ ?>
	<?php $page_background = ''; ?>
		<div class="limoking-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background)? '': 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?> >
			<div class="limoking-page-title-overlay"></div>
			<div class="limoking-page-title-container container" >
				<h1 class="limoking-page-title"><?php the_title(); ?></h1>
				<?php if( !empty($limoking_post_option['page-caption']) ){ ?>
				<span class="limoking-page-caption"><?php echo limoking_text_filter(limoking_escape_string($limoking_post_option['page-caption'])); ?></span>
				<?php } ?>
			</div>	
		</div>	
	<?php }else if( is_single() && $post->post_type == 'post' ){ 
	
		if( !empty($limoking_post_option['page-title']) ){
			$page_title = $limoking_post_option['page-title'];
			$page_caption = $limoking_post_option['page-caption'];
		}else{
			$page_title = $theme_option['post-title'];
			$page_caption = $theme_option['post-caption'];
		} 
	?>
		<div class="limoking-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background)? '': 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?> >
			<div class="limoking-page-title-overlay"></div>
			<div class="limoking-page-title-container container" >
				<h3 class="limoking-page-title"><?php echo limoking_text_filter(limoking_escape_string($page_title)); ?></h3>
				<?php if( !empty($page_caption) ){ ?>
				<span class="limoking-page-caption"><?php echo limoking_text_filter(limoking_escape_string($page_caption)); ?></span>
				<?php } ?>
			</div>	
		</div>	
	<?php }else if( is_single() ){ // for custom post type
		
		$page_title = get_the_title();
		if( !empty($limoking_post_option) && !empty($limoking_post_option['page-caption']) ){
			$page_caption = $limoking_post_option['page-caption'];
		}else if($post->post_type == 'portfolio' && !empty($theme_option['page-caption']) ){
			$page_caption = $theme_option['portfolio-caption'];		
		}
	?>
		<div class="limoking-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background)? '': 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?> >
			<div class="limoking-page-title-overlay"></div>
			<div class="limoking-page-title-container container" >
				<h1 class="limoking-page-title"><?php echo limoking_text_filter(limoking_escape_string($page_title)); ?></h1>
				<?php if( !empty($page_caption) ){ ?>
				<span class="limoking-page-caption"><?php echo limoking_text_filter(limoking_escape_string($page_caption)); ?></span>
				<?php } ?>
			</div>	
		</div>	
	<?php }else if( is_404() ){ ?>
		<div class="limoking-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background)? '': 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?>  >
			<div class="limoking-page-title-overlay"></div>
			<div class="limoking-page-title-container container" >
				<h1 class="limoking-page-title"><?php esc_html_e('404', 'limoking'); ?></h1>
				<span class="limoking-page-caption"><?php esc_html_e('Page not found', 'limoking'); ?></span>
			</div>	
		</div>		
	<?php }else if( is_archive() || is_search() ){
		if( is_search() ){
			$title = esc_html__('Search Results', 'limoking');
			$caption = get_search_query();
		}else if( is_category() || is_tax('portfolio_category') || is_tax('product_cat') ){
			$title = esc_html__('Category','limoking');
			$caption = single_cat_title('', false);
		}else if( is_tag() || is_tax('portfolio_tag') || is_tax('product_tag') ){
			$title = esc_html__('Tag','limoking');
			$caption = single_cat_title('', false);
		}else if( is_day() ){
			$title = esc_html__('Day','limoking');
			$caption = get_the_date('F j, Y');
		}else if( is_month() ){
			$title = esc_html__('Month','limoking');
			$caption = get_the_date('F Y');
		}else if( is_year() ){
			$title = esc_html__('Year','limoking');
			$caption = get_the_date('Y');
		}else if( is_author() ){
			$title = esc_html__('By','limoking');
			
			$author_id = get_query_var('author');
			$author = get_user_by('id', $author_id);
			$caption = $author->display_name;					
		}else if( is_post_type_archive('product') ){
			$title = esc_html__('Shop', 'limoking');
			$caption = '';
		}else{
			$title = get_the_title();
			$caption = '';
		}
	?>
		<div class="limoking-page-title-wrapper <?php echo esc_attr($header_style . '-title-wrapper'); ?>" <?php echo empty($header_background)? '': 'style="background-image: url(\'' . esc_url($header_background) . '\'); "'; ?> >
			<div class="limoking-page-title-overlay"></div>
			<div class="limoking-page-title-container container" >
				<span class="limoking-page-title"><?php echo limoking_text_filter(limoking_escape_string($title)); ?></span>
				<?php if( !empty($caption) ){ ?>
				<h1 class="limoking-page-caption"><?php echo limoking_text_filter(limoking_escape_string($caption)); ?></h1>
				<?php } ?>
			</div>	
		</div>		
	<?php } ?>
	<!-- is search -->