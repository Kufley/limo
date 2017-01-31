<?php
	/*	
	*	Goodlayers Layerslider Support File
	*/
	
	if(!function_exists('limoking_get_layerslider_list')){
		function limoking_get_layerslider_list(){
			if( !function_exists('lsSliders') ) return;
		
			$ret = array();
			$sliders = lsSliders();
			foreach($sliders as $slider){
				$ret[$slider['id']] = $slider['name'];
			}
			return $ret;
		}
	}
	
	add_action('limoking_print_item_selector', 'limoking_check_layerslider_item', 10, 2);
	if( !function_exists('limoking_check_layerslider_item') ){
		function limoking_check_layerslider_item( $type, $settings = array() ){
			$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

			global $limoking_spaces;
			$margin = (!empty($settings['margin-bottom']) && 
				$settings['margin-bottom'] != $limoking_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
			$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';	
		
			if($type == 'layer-slider'){
				echo '<div class="limoking-layerslider-item limoking-slider-item limoking-item" ' . $item_id . $margin_style . ' >';
				echo '<div class="limoking-ls-shadow limoking-top" ></div>';
				echo do_shortcode('[layerslider id="' . $settings['id'] . '"]');
				echo '<div class="limoking-ls-shadow limoking-bottom" ></div>';
				echo '</div>';
			}
		}
	}	
	
	add_action('layerslider_ready', 'limoking_layerslider_overrides');
	if( !function_exists('limoking_layerslider_overrides') ){
		function limoking_layerslider_overrides() {
			$GLOBALS['lsAutoUpdateBox'] = false;
		}
	}
	
?>