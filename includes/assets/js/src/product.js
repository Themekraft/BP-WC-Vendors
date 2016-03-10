/*Front end product meta boxes */
jQuery( function( $ ){

	// PRODUCT TYPE SPECIFIC OPTIONS
	$( 'select#product-type' ).change( function () {

		// Get value
		var select_val = $( this ).val();

		if ( 'variable' === select_val ) {
			$( 'input#_manage_stock' ).change();
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		} else if ( 'grouped' === select_val ) {
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		} else if ( 'external' === select_val ) {
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		}

		show_and_hide_panels();

		$( 'ul.wc-tabs li:visible' ).eq(0).find( 'a' ).click();

		$( 'body' ).trigger( 'woocommerce-product-type-change', select_val, $( this ) );

	}).change();

	$('input#_downloadable, input#_virtual').change(function(){
		show_and_hide_panels();
	});

	// Sale price schedule
	$('.sale_price_dates_fields').each(function() { 

		var sale_schedule_set = false; 

		$('.sale_price_dates_fields').find('input').each(function(){
			if ( $(this).val() != '' )
					sale_schedule_set = true;
		});

		if ( sale_schedule_set ) {

			$('.sale_schedule').hide();
			$('.sale_price_dates_fields').show();

		} else {

			$('.sale_schedule').show();
			$('.sale_price_dates_fields').hide();

		}
	}); 

	$('.sale_schedule').on( 'click', function() {
		$('.sale_price_dates_fields').show(); 
		$(this).hide(); 
		$('.cancel_sale_schedule').show(); 
		return false;
	});

	$('.cancel_sale_schedule').on( 'click', function() {
		$('.sale_price_dates_fields').hide();
		$(this).hide(); 
		$('.sale_schedule').show(); 
		return false;
	});


	function show_and_hide_panels() {
		var product_type    = $('#product-type').val();
		var is_virtual      = $('#_virtual').is(':checkbox') ? $('input#_virtual:checked').size() : $('#_virtual').val();
		var is_downloadable = $('#_downloadable').is(':checkbox') ? $('input#_downloadable:checked').size() : $('#_downloadable').val();

		// Hide/Show all with rules
		var hide_classes = '.hide_if_downloadable, .hide_if_virtual';
		var show_classes = '.show_if_downloadable, .show_if_virtual, .show_if_external';

		$.each( wcv_frontend_product.product_types, function( index, value ) {
			hide_classes = hide_classes + ', .hide_if_' + value;
			show_classes = show_classes + ', .show_if_' + value;
		} );

		$( hide_classes ).show();
		$( show_classes ).hide();

		// Shows rules
		if ( is_downloadable ) {
			$('.show_if_downloadable').show();
		}
		if ( is_virtual ) {
			$('.show_if_virtual').show();
		}

        $('.show_if_' + product_type).show();

		// Hide rules
		if ( is_downloadable ) {
			$('.hide_if_downloadable').hide();
		}
		if ( is_virtual ) {
			$('.hide_if_virtual').hide();
		}

		if ( product_type == "grouped" ) {
	
			Ink.requireModules( ['Ink.Dom.Selector_1','Ink.UI.Tabs_1'], function( Selector, Tabs ){
        		var tabsObj = new Tabs('#wcv-tabs');
        		tabsObj.changeTab('#linked_product'); 

    		});
		}

		$('.hide_if_' + product_type).hide();

		$('input#_manage_stock').change();
	}

	
	// STOCK OPTIONS
	$('input#_manage_stock').change(function(){
		if ( $(this).is(':checked') ) {
			$('div.stock_fields').show();
		} else {
			$('div.stock_fields').hide();
		}
	}).change();


	// FEATURED IMAGE 
	// Setting uploader type to true allows multiple selections as required by gallery 
	// todo make translatable 
	function featured_image_uploader()
	{

		var media_uploader, json;

		var title 			= $( '.wcv-featuredimg' ).data( 'title' ); 
		var button_text 	= $( '.wcv-featuredimg' ).data( 'button_text' ); 

		if (undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    media_uploader = wp.media({
      		title: title,
      		button: {
        		text: button_text
      		},
      		multiple: false  // Set to true to allow multiple files to be selected
    	});

	    media_uploader.on( 'select' , function(){
	    	json = media_uploader.state().get('selection').first().toJSON(); 

	    	if ( 0 > $.trim( json.url.length ) ) {
		        return;
		    }

		    $( '.wcv-featuredimg' )
		    	.append( '<img src="'+ json.sizes.thumbnail.url + '" alt="' + json.caption + '" title="' + json.title +'" style="max-width: 100%;" />'); 
		    $('#_featured_image_id').val(json.id); 

		    $('.wcv-media-uploader-featured-add').addClass('hidden'); 
		    $('.wcv-media-uploader-featured-delete').removeClass('hidden'); 


	    });

	    media_uploader.open();
	}

	// Handle Add Featured Image 
	$('.wcv-media-uploader-featured-delete').on('click', function(e) { 
		e.preventDefault(); 
		// reset the data so that it can be removed and saved. 
		$('.wcv-featuredimg').html(''); 
		$('.wcv-media-uploader-featured-delete').addClass('hidden'); 
		$('.wcv-media-uploader-featured-add').removeClass('hidden'); 

	});

	// Handle Remove Featured Image 
	$('.wcv-media-uploader-featured-add').on( 'click', function(e) { 
		e.preventDefault(); 
		featured_image_uploader(); 
		return false; 
	}); 

	// PRODUCT IMAGE GALLERY 

	/* 
		Product Gallery Uploader 
	*/
	function product_gallery_uploader() { 

		var media_uploader, json;
		var $image_gallery_ids = $('#product_image_gallery');
		var $product_images = $('#product_images_container ul.product_images');
		var attachment_ids = $image_gallery_ids.val();
		var $el = $('.wcv-media-uploader-gallery a'); 

		if (undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    // Create the media frame.
		media_uploader = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			multiple: true,
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable :	'all'
				})
			]
		});

	    media_uploader.on( 'select' , function( ) { 
	    	
	    	var selection = media_uploader.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
					attachment_image = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$product_images.append('\
						<li class="wcv-gallery-image" data-attachment_id="' + attachment.id + '">\
							<img src="' + attachment_image + '" />\
							<ul class="actions">\
								<li><a href="#" class="delete" title="' + $el.data('delete') + '"><i class="fa fa-times"></i></a></li>\
							</ul>\
						</li>');
				}

			});

			$image_gallery_ids.val( attachment_ids );

	    });

	    if ( check_gallery_count() ){ 
	    	var gallery_max_msg = $( '#product_images_container' ).data( 'gallery_max_notice' ); 
	    	alert( gallery_max_msg ); 
	    } else { 
	    	// Open the modal 
	   		media_uploader.open();
	    }

	}

	// Remove images
	$('#product_images_container').on( 'click', 'a.delete', function(e) {

		var $image_gallery_ids = $('#product_image_gallery');

		e.preventDefault(); 
		$(this).closest('li.wcv-gallery-image').remove();

		var attachment_ids = '';

		$('#product_images_container ul li.wcv-gallery-image').css('cursor','default').each(function() {
			var attachment_id = jQuery(this).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		// remove any lingering tooltips
		$( '#tiptip_holder' ).removeAttr( 'style' );
		$( '#tiptip_arrow' ).removeAttr( 'style' );

		return false;
	});

	$('.wcv-media-uploader-gallery').on( 'click' , function(e) { 
		e.preventDefault(); 
		product_gallery_uploader(); 
		return false; 
	}); 


	function check_gallery_count(){ 

		var gallery_count 	= $( '.wcv-gallery-image' ).length; 
		var gallery_max 	= $( '#product_images_container' ).data( 'gallery_max_upload' ) -1; 
		
		return ( gallery_count > gallery_max ) ? true : false; 

	}

	// 
	//  File downloads 
	// 
	

	// File inputs
	$('#files_download').on('click','.downloadable_files a.insert',function(){
		$(this).closest('.downloadable_files').find('tbody').append( $(this).data( 'row' ) );
		return false;
	});

	$('#files_download').on('click','.downloadable_files a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});

	// Uploading files
	var downloadable_file_frame;
	var file_path_field;

	$(document).on( 'click', '.upload_file_button', function( event ){

		var $el = $(this);

		file_path_field = $el.closest('tr').find('td.file_url input');

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( downloadable_file_frame ) {
			downloadable_file_frame.open();
			return;
		}

		var downloadable_file_states = [
			// Main states.
			new wp.media.controller.Library({
				library:   wp.media.query(),
				multiple:  true,
				title:     $el.data('choose'),
				priority:  20,
				filterable: 'uploaded',
			})
		];

		// Create the media frame.
		downloadable_file_frame = wp.media.frames.downloadable_file = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			library: {
				type: ''
			},
			button: {
				text: $el.data('update'),
			},
			multiple: true,
			states: downloadable_file_states,
		});

		// When an image is selected, run a callback.
		downloadable_file_frame.on( 'select', function() {

			var file_path = '';
			var selection = downloadable_file_frame.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.url )
					file_path = attachment.url

			} );

			file_path_field.val( file_path );
		});

		// Set post to 0 and set our custom type
		downloadable_file_frame.on( 'ready', function() {
			downloadable_file_frame.uploader.options.uploader.params = {
				type: 'downloadable_product'
			};
		});

		// Finally, open the modal.
		downloadable_file_frame.open();
	});

	// Download ordering
	$('.downloadable_files tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: 'td.sort',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
	});

	// 
	//  Shipping Rates 
	// 

	// Flat Rates 
	function enable_disable( disable_input, toggle_inputs ){ 

		if ( $( disable_input ).is(':checked') ) {
			toggle_inputs.prop( 'disabled', true ); 
			
			toggle_inputs.each(function() {
			  if ( $(this).is(':checkbox') ) { 
			  	$(this).removeAttr('checked');
			  } else { 
			  	$(this).val(''); 
			  }
			});

		} else {
			toggle_inputs.prop( 'disabled', false ); 
		}
	}

	// Disable national shipping 
	$( '#_shipping_fee_national_disable' ).change(function() { enable_disable( $( this ), $( '.wcv-disable-national-input' ) ); } ); 
	// Toggle Free shipping 
	$( '#_shipping_fee_national_free' ).change(function() { enable_disable( $( this ), $( '#_shipping_fee_national' ) ); } ); 

	// International 
	// Disable international shipping 
	$( '#_shipping_fee_international_disable' ).change(function() { enable_disable( $( this ), $( '.wcv-disable-international-input' ) ); } ); 
	// Free shipping 
	$( '#_shipping_fee_international_free' ).change(function() { enable_disable( $( this ), $( '#_shipping_fee_international' ) ); } ); 

	// Country Rates 
	$('#shipping').on('click','.wcv_shipping_rates a.insert',function(){
		$(this).closest('.wcv_shipping_rates').find('tbody').append( $(this).data( 'row' ) );
		return false;
	});

	$('#shipping').on('click','.wcv_shipping_rates a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});

	// shipping rate ordering
	$('.wcv_shipping_rates tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: 'td.sort',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
	});

	// // Overrides for other plugins hooking into product meta - currently not used
	// $('.woocommerce_options_panel').addClass('content').removeClass('panel').wrapInner('<div class="large-9 small-9 column right"></div>').wrapInner('<div class="row"></div>');  
	
	// // style the data tab correctly - currently not used 
	// $('.product_data_tabs').find('li').each(function() { return !$(this).hasClass('tab-title'); }).addClass('tab-title');

	// Enable selects to use the select2 enhanced select
	$('.select2').select2(); 

	show_and_hide_panels();

	// On load shipping enable and disable 
	enable_disable( $( '#_shipping_fee_national_free' ),  $( '#_shipping_fee_national' ) ); 
	enable_disable( $( '#_shipping_fee_international_free' ),  $( '#_shipping_fee_international' ) ); 
	enable_disable( $( '#_shipping_fee_national_disable' ),  $( '.wcv-disable-national-input' )  ); 
	enable_disable( $( '#_shipping_fee_international_disable' ), $( '.wcv-disable-international-input' ) );

	function confirm_delete() { 
		var confirm_text = $('.confirm_delete').data('confirm_text'); 
		confirm( confirm_text )
	} 

	$('.confirm_delete').on( 'click', function() { 
		confirm_delete(); 
	}); 

		
});