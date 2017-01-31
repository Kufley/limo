(function($){

	// bind an event to upload 	
	$.fn.gdlrUploadFont = function(){
		var font_wrapper = $(this);
		var font_input = $(this).children('.limoking-upload-font-input');
		var font_container = $(this).children('.limoking-upload-font-container');
		
		// bind the existing font
		font_container.find('.limoking-font-item-wrapper').each(function(){
			$(this).gdlrbindFontItemOptions( font_wrapper );
		});
		
		// bind adding font option
		$(this).find('.limoking-add-more-font').click(function(){
			var font_item = $(this).siblings('.limoking-font-item-wrapper').clone();
			
			font_item.gdlrbindFontItemOptions( font_wrapper );
			font_item.hide().appendTo(font_container).slideDown();
			
			font_wrapper.gdlrUpdateUploadedFont();
		});
	}
	
	// update the font when the changes is made
	$.fn.gdlrUpdateUploadedFont = function( font_wrapper ){
		var font_input = [];
		var font_container = $(this).children('.limoking-upload-font-container');
		font_container.find('.limoking-font-item-wrapper').each(function(){
			var subfont = new Object();
			$(this).find('input[type="text"]').each(function(){
				if( $(this).attr('data-id') ){
					subfont[$(this).attr('data-type')] = $(this).attr('data-id');
				}else{
					subfont[$(this).attr('data-type')] = $(this).val();
				}
			});
			font_input.push(subfont);
		});
		
		$(this).children('.limoking-upload-font-input').val(JSON.stringify(font_input));
	}
	
	// bind the font option
	$.fn.gdlrbindFontItemOptions = function( font_wrapper ){
		var font_item = $(this);
		
		// delete font button
		$(this).children('.limoking-delete-font-item').click(function(){
			$('body').limoking_confirm({
				success: function(){
					font_item.slideUp(function(){
						$(this).remove();
						font_wrapper.gdlrUpdateUploadedFont();
					});
				}
			});
		});
		
		// change the font input
		$(this).find('input[type="text"]').change(function(){
			$(this).removeAttr('data-id');
			font_wrapper.gdlrUpdateUploadedFont();
		});
		
		// select font function
		$(this).find('.limoking-upload-font-button').click(function(){
			var upload_button = $(this);
		
			var custom_uploader = wp.media({
				title: upload_button.attr('data-title'),
				button: { text: upload_button.attr('data-button') },
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				
				upload_button.siblings('.limoking-font-input')
					.val(attachment.url)
					.attr('data-id', attachment.id);
				font_wrapper.gdlrUpdateUploadedFont();
			}).open();			
		});		
	}
	
	// skin option
	$.fn.gdlrSkinGenerator = function(){
		var container = $(this).children('.limoking-skin-container');
		var textarea = $(this).children('textarea');
		
		// generate default option
		var default_options = $.parseJSON($(this).find('.limoking-default-skin').html());
		var default_item = '<div class="limoking-skin-item-wrapper" >';
		default_item += '<div class="skin-option-item">';
		default_item += '<div class="skin-option-item-title">Default Title</div>';
		default_item += '<div class="skin-option-item-edit"></div>';
		default_item += '<div class="skin-option-item-delete"></div>';
		default_item += '</div>'; // skin-option-item
		default_item += '<div class="limoking-skin-option-wrapper" >';
		for(var slug in default_options){
			default_item += '<div class="skin-option">';
			default_item += '<div class="skin-color-title">' + default_options[slug] + '</div>';
			default_item += '<input type="text" data-name="' + slug + '" class="wp-color-picker" value="#ffffff" data-default-color="#ffffff" />';
			default_item += '</div>';
		}
		default_item += '</div>';
		default_item += '</div>'; // limoking-skin-item-wrapper
		
		// init item
		var old_options = $.parseJSON(textarea.val());
		old_options = (old_options)? old_options: [];
		for(var i=0; i<old_options.length; i++){
			var new_item = $(default_item);
			container.append(new_item);
			
			new_item.find('.wp-color-picker').each(function(){
				$(this).val(old_options[i][$(this).attr('data-name')]);
				$(this).wpColorPicker({
					change: function(){ textarea.gdlrUpdateSkinOption(container); }
				});				
			});
			
			var item_title = new_item.find('.skin-option-item-title');
			item_title.html(old_options[i]['skin-title']);
			item_title.siblings('.skin-option-item-delete').click(function(){
				$(this).closest('.limoking-skin-item-wrapper').slideUp(function(){
					$(this).remove();
					textarea.gdlrUpdateSkinOption(container);
				});
			});
			item_title.siblings('.skin-option-item-edit').click(function(){
				$(this).parent().siblings('.limoking-skin-option-wrapper').slideToggle();
			});
			new_item.slideDown();			
			
		}
			
		// add new skin
		$(this).find('.limoking-add-more-skin').click(function(){
			if(!$(this).siblings('.gdl-text-input').val()){
				$('body').limoking_alert({ text: 'Please fill the skin name', duration: 2000, status: 'failed'});
				return;
			}

			var new_item = $(default_item).hide();
			container.append(new_item);
			
			// bind events
			new_item.find('.limoking-skin-option-wrapper').css('display', 'block');
			new_item.find('.wp-color-picker').each(function(){
				$(this).wpColorPicker({
					change: function(){ textarea.gdlrUpdateSkinOption(container); }
				});
			});
			
			var item_title = new_item.find('.skin-option-item-title');
			item_title.html($(this).siblings('.gdl-text-input').val());
			item_title.siblings('.skin-option-item-delete').click(function(){
				$(this).closest('.limoking-skin-item-wrapper').slideUp(function(){
					$(this).remove();
					textarea.gdlrUpdateSkinOption(container);
				});
			});
			item_title.siblings('.skin-option-item-edit').click(function(){
				$(this).parent().siblings('.limoking-skin-option-wrapper').slideToggle();
			});
			new_item.slideDown();
			
			textarea.gdlrUpdateSkinOption(container);
		});
	}	
	
	$.fn.gdlrUpdateSkinOption = function(container){
		var skin_val = [];
		container.children().each(function(){
			var skin = new Object();
			$(this).find('input[data-name]').each(function(){
				eval('skin["' + $(this).attr('data-name') + '"]=$(this).val()');
			});
			$(this).find('.skin-option-item-title').each(function(){
				skin['skin-title'] = $(this).html();
			});
			skin_val.push(skin);
		});
		
		$(this).val(JSON.stringify(skin_val));
	}
	
	// facilities and service option
	$.fn.gdlrUpdateCarinfo = function( textarea ){
		var data = [];
		
		$(this).find('.limoking-carinfo-item-wrapper').each(function(){
			var item = new Object;
			item.icon = $(this).find('[data-slug="carinfo-icon"]').val();
			item.value = $(this).find('[data-slug="carinfo-value"]').val();
			
			data.push(item);
		});
		textarea.val(JSON.stringify(data));
	}
	$.fn.gdlrCarinfo = function(){
		if($(this).length <= 0) return;
		
		var data = $.parseJSON($(this).val());
		if( !data ){ data = []; }
		
		var textarea = $(this);
		var wrapper = $('<div class="limoking-carinfo-wrapper"></div>');
		var add_new = $('<span class="limoking-carinfo-add-new"><i class="fa fa-plus icon-plus"></i></span>')
		var template = $(
			'<div class="limoking-carinfo-item-wrapper">\
				<span class="limoking-carinfo-item-remove"><i class="fa fa-minus icon-minus" ></i></span>\
				<input class="limoking-carinfo-item-icon" placeholder="icon-class" data-slug="carinfo-icon" />\
				<input class="limoking-carinfo-item-value" placeholder="value" data-slug="carinfo-value" />\
			 </div>'
		);
		
		for (var i=0; i<data.length; i++){
			var clone = template.clone();
			
			if( data[i].icon ){
				clone.find('[data-slug="carinfo-icon"]').val(data[i].icon);
			}
			if( data[i].value ){
				clone.find('[data-slug="carinfo-value"]').val(data[i].value);
			}
			wrapper.append(clone);
		}
		
		// add new button clicked
		add_new.click(function(){
			var clone = template.clone().hide();
			wrapper.append(clone);
			clone.slideDown(200);
			wrapper.gdlrUpdateCarinfo( textarea );
		});
		
		// remove button clicked
		wrapper.on('click', '.limoking-carinfo-item-remove', function(){
			var current = $(this);
			$('body').limoking_confirm({ success: function(){
				current.closest('.limoking-carinfo-item-wrapper').slideUp(200, function(){ 
					$(this).remove(); 
					wrapper.gdlrUpdateCarinfo( textarea );
				});
			}});
		});
		
		// update data after changed
		wrapper.on('change', 'input', function(){
			wrapper.gdlrUpdateCarinfo( textarea );
		});
		
		// insert item to page option
		console.log($(this).closest('.limoking-option-input').siblings('.limoking-option-title'));
		$(this).closest('.limoking-option-input').siblings('.limoking-option-title').prepend(add_new);
		wrapper.insertBefore($(this));
	}	
	
	// execute the script when document is ready
	$(document).ready(function(){

		// set the color picker
		$('.limoking-option-input .wp-color-picker').wpColorPicker();		
		
		// animate combobox
		$('.limoking-option-input select').not('multiple').change(function(){
			var wrapper = $(this).attr('data-slug') + '-wrapper';
			var selected_wrapper = $(this).val() + '-wrapper';
			$(this).parents('.limoking-option-wrapper').siblings('.' + wrapper).each(function(){
				if($(this).hasClass(selected_wrapper)){
					$(this).slideDown(300);
				}else{
					$(this).slideUp(300);
				}
			});
		});
		$('.limoking-option-input select').not('multiple').each(function(){
			var wrapper = $(this).attr('data-slug') + '-wrapper';
			var selected_wrapper = $(this).val() + '-wrapper';

			$(this).parents('.limoking-option-wrapper').siblings('.' + wrapper).each(function(){
				if($(this).hasClass(selected_wrapper)){
					$(this).css('display', 'block');
				}else{
					$(this).css('display', 'none');
				}
			});
		});		
				
		// animate radio image 
		$('.limoking-option-input input[type="radio"]').change(function(){
			$(this).parent().siblings('label').children('input').attr('checked', false); 
			$(this).parent().addClass('active').siblings('label').removeClass('active');
			
			// animate the related section
			var wrapper = $(this).attr('data-slug') + '-wrapper';
			var selected_wrapper = $(this).val() + '-wrapper';
			$(this).parents('.limoking-option-wrapper').siblings('.' + wrapper).each(function(){
				if($(this).hasClass(selected_wrapper)){
					$(this).slideDown(300);
				}else{
					$(this).slideUp(300);
				}
			});
		});
		$('.limoking-option-input input[type="radio"]:checked').each(function(){
		
			// trigger the default value
			var wrapper = $(this).attr('data-slug') + '-wrapper';
			var selected_wrapper = $(this).val() + '-wrapper';

			$(this).parents('.limoking-option-wrapper').siblings('.' + wrapper).each(function(){
				if($(this).hasClass(selected_wrapper)){
					$(this).css('display', 'block');
				}else{
					$(this).css('display', 'none');
				}
			});
		});		
		
		// animate checkbox
		$('.limoking-option-input input[type="checkbox"]').click(function(){	
			if( $(this).siblings('.checkbox-appearance').hasClass('enable') ){
				$(this).siblings('.checkbox-appearance').removeClass('enable');
			}else{
				$(this).siblings('.checkbox-appearance').addClass('enable');
			}
		});
		
		// animate date picker
		$('.limoking-option-input input.limoking-date-picker').datepicker({
			dateFormat : 'yy-mm-dd',
			changeMonth: true,
			changeYear: true 
		});
		
		// animate upload button
		$('.limoking-option-input .limoking-upload-box-input').change(function(){		
			$(this).siblings('.limoking-upload-box-hidden').val($(this).val());
			if( $(this).val() == '' ){ 
				$(this).siblings('.limoking-upload-img-sample').addClass('blank'); 
			}else{
				$(this).siblings('.limoking-upload-img-sample').attr('src', $(this).val()).removeClass('blank');
			}
		});
		$('.limoking-option-input .limoking-upload-box-button').click(function(){
			var upload_button = $(this);
			var data_type = upload_button.attr('data-type');
			if( data_type == 'all' ){ data_type = ''; }
			
			var custom_uploader = wp.media({
				title: upload_button.attr('data-title'),
				button: { text: upload_button.attr('data-button') },
				library : { type : data_type },
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				
				if( data_type == 'image' ){
					upload_button.siblings('.limoking-upload-img-sample').attr('src', attachment.url).removeClass('blank');
				}
				upload_button.siblings('.limoking-upload-img-sample').attr('src', attachment.url).removeClass('blank');
				upload_button.siblings('.limoking-upload-box-input').val(attachment.url);
				upload_button.siblings('.limoking-upload-box-hidden').val(attachment.id);
			}).open();			
		});
		
		// animate sliderbar item
		$('.limoking-option-input .limoking-sliderbar').each(function(){
			$(this).slider({ min:10, max:72, value: $(this).attr('data-value'),
				slide: function(event, ui){
					$(this).siblings('.limoking-sliderbar-text-hidden').val(ui.value);
					$(this).siblings('.limoking-sliderbar-text').html(ui.value + ' px');
				}
			});
		});		
		
		// animate skin
		$('#skin-setting-wrapper').each(function(){ $(this).gdlrSkinGenerator(); });
		
		// animate the font section
		$('#upload-font-wrapper').gdlrUploadFont();
		
		// animate font family section
		var limoking_custom_font_list = [];
		$('select.limoking-font-combobox').change(function(){
			var font_family = $(this).val();
			var sample_font = $(this).parent().siblings('.limoking-sample-font');
			var selected_option = $(this).children('option:selected');
			
			if( selected_option.attr('data-type') == 'web-safe-font' ){
				sample_font.css('font-family', font_family);
			}else if( selected_option.attr('data-type') == 'google-font' ){
				$('head').append( $('<link rel="stylesheet" type="text/css" />').attr('href', selected_option.attr('data-url')) );
				sample_font.css('font-family', font_family + ', BlankSerif');
			}else if( selected_option.attr('data-type') == 'custom-font' ){
				if( limoking_custom_font_list.indexOf(font_family) <= 0 ){
					var new_font = '@font-face {';
					new_font    += 'font-family: "' + font_family + '";'
					new_font    += 'src: url("' + selected_option.attr('data-eot') + '");';
					new_font    += 'src: url("' + selected_option.attr('data-eot') + '?#iefix") format("embedded-opentype"),';
					new_font    += 'url("' + selected_option.attr('data-ttf') + '") format("truetype");';
					new_font    += '}';
					
					$('head').append($('<style type="text/css"></style>').append(new_font));
					limoking_custom_font_list.push(font_family);
				}
				sample_font.css('font-family', font_family + ', BlankSerif');
			}
		
		});
		$('select.limoking-font-combobox').trigger('change');
		
		// initiate slider selector		
		$('textarea.limoking-slider-selection').each(function(){
			$(this).gdlrCreateSliderSelection();	
		});
		
		// carinfo
		$('#limoking-carinfo').gdlrCarinfo();
		
	});	
	
})(jQuery);