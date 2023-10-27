(function($) {

	"use strict";

	/* Process Product Resave */
	$(document).on('submit', '.blueparrot-product-resave-form', function(e) {

		e.preventDefault();

		var obj_ele			= $(this);
		var submitbutton	= obj_ele.find( 'input[type="submit"]' );

		if ( ! submitbutton.hasClass( 'button-disabled' ) ) {

			submitbutton.addClass( 'button-disabled' );

			obj_ele.find('.notice-wrap').remove();
			obj_ele.append( '<div class="notice-wrap"><span class="spinner is-active"></span><div class="blueparrot-progress"><div></div></div></div>' );

			obj_ele.find('.blueparrot-progress-wrap').show();
			obj_ele.find('.blueparrot-product-update-result-wrp').show();
			obj_ele.find('.blueparrot-progress-wrap .blueparrot-progress-strip').css('width', 0);

			blueparrot_product_resave_process( null, obj_ele );
		}
		return false;
	});

})(jQuery);

/* Function to resave the products */
function blueparrot_product_resave_process( data, obj_ele ) {

	if( ! data ) {
		var form_data	= obj_ele.serialize();
		var data		= {
			action		: 'blueparrot_product_resave_action',
			form_data	: form_data,
			page		: 1,
			is_ajax		: 1,
		};
	}

	jQuery.post(ajaxurl, data, function(result) {

		var notice_wrap = obj_ele.find('.notice-wrap');

		if( result.status == 0 ) {

			notice_wrap.html('<div class="updated error"><p>' + result.message + '</p></div>');
			obj_ele.find('.button-disabled').removeClass('button-disabled');

		} else {

			jQuery('.blueparrot-product-update-result-wrp').append( result.result_message );
			jQuery('.blueparrot-product-result-percent').html( result.percentage );

			jQuery('.blueparrot-progress div').animate({
				width: result.percentage + '%',
			}, 50, function() {

			});

			/* If data is there then process again */
			if( result.data_process != 0 && ( result.data_process < result.total_count ) ) {
				data['page']            = result.page;
				data['total_count']     = result.total_count;
				data['data_process']    = result.data_process;
				blueparrot_product_resave_process( data, obj_ele );
			}

			/* If process is done */
			if( result.data_process >= result.total_count ) {

				notice_wrap.html('<div id="blueparrot-batch-success" class="updated notice"><p>' + result.message + '</p></div>');
				obj_ele.find('.button-disabled').removeClass('button-disabled');

				if( result.url ) {
					window.location = result.url;
				}
			}
		}
	});
}