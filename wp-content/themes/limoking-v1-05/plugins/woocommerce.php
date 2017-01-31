<?php
	/*	
	*	Goodlayers Woocommerce Support File
	*/
	
	add_theme_support( 'woocommerce' );
	
	if(!function_exists('limoking_get_woocommerce_nav')){
		function limoking_get_woocommerce_nav( $icon_style = 'dark' ){
			global $woocommerce;
			if(!empty($woocommerce)){
?>	
<div class="limoking-top-woocommerce-wrapper">
	<div class="limoking-top-woocommerce-button">
		<?php echo '<span class="limoking-cart-item-count">' . $woocommerce->cart->cart_contents_count . '</span>'; ?>
		<img src="<?php echo get_template_directory_uri() . '/images/cart-' . $icon_style . '.png'; ?>" alt="" width="83" height="71" />
	</div>
	<div class="limoking-top-woocommerce">
	<div class="limoking-top-woocommerce-inner">
		<?php 
			echo '<div class="limoking-cart-count" >';
			echo '<span class="head">' . esc_html__('Items : ', 'limoking') . ' </span>';
			echo '<span class="limoking-cart-item-count">' . $woocommerce->cart->cart_contents_count . '</span>'; 
			echo '</div>';
			
			echo '<div class="limoking-cart-amount" >';
			echo '<span class="head">' . esc_html__('Subtotal :', 'limoking') . ' </span>';
			echo '<span class="limoking-cart-sum-amount">' . $woocommerce->cart->get_cart_total() . '</span>';
			echo '</div>';
		?>
		<a class="limoking-cart-button" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" >
			<?php echo esc_html__('View Cart', 'limoking'); ?>
		</a>
		<a class="limoking-checkout-button" href="<?php echo esc_url($woocommerce->cart->get_checkout_url()); ?>" >
			<?php echo esc_html__('Check Out', 'limoking'); ?>
		</a>
	</div>
	</div>
</div>
<?php		
			}
		}
	}
	
	// filter for ajax content
	add_filter('add_to_cart_fragments', 'limoking_woocommerce_cart_ajax');
	function limoking_woocommerce_cart_ajax( $fragments ) {
		global $woocommerce;
		
		ob_start();
		$fragments['span.limoking-cart-item-count'] = '<span class="limoking-cart-item-count">' . $woocommerce->cart->cart_contents_count . '</span>'; 
		$fragments['span.limoking-cart-sum-amount'] = '<span class="limoking-cart-sum-amount">' . $woocommerce->cart->get_cart_total() . '</span>';
		ob_end_clean();
		
		return $fragments;
	}	
	
	// Change number or products per row to 3
	add_filter('loop_shop_columns', 'limoking_woo_loop_columns');
	if (!function_exists('limoking_woo_loop_columns')) {
		function limoking_woo_loop_columns() {
			global $theme_option;
			return empty($theme_option['all-products-per-row'])? 3: $theme_option['all-products-per-row'];
		}
	}
	add_filter('post_class', 'limoking_woo_column_class');
	if (!function_exists('limoking_woo_column_class')) {
		function limoking_woo_column_class($classes) {
			global $theme_option;
			$item_per_row = empty($theme_option['all-products-per-row'])? 3: $theme_option['all-products-per-row'];
			
			if( is_archive() && get_post_type() == 'product'){
				switch($item_per_row){
					case 1: $classes[] = 'limoking-1-product-per-row'; break;
					case 2: $classes[] = 'limoking-2-product-per-row'; break;
					case 3: $classes[] = 'limoking-3-product-per-row'; break;
					case 4: $classes[] = 'limoking-4-product-per-row'; break;
					case 5: $classes[] = 'limoking-5-product-per-row'; break;
				}
			}
			return $classes;
		}
	}	
	
	// add action to enqueue woocommerce style
	add_filter('limoking_enqueue_scripts', 'limoking_regiser_woo_style');
	if( !function_exists('limoking_regiser_woo_style') ){
		function limoking_regiser_woo_style($array){	
			global $woocommerce;
			if( !empty($woocommerce) ){
				$array['style']['limoking-woo-style'] = get_template_directory_uri() . '/stylesheet/gdlr-woocommerce.css';
			}
			return $array;
		}
	}
	
?>