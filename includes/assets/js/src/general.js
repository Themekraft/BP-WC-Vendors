jQuery( function( $ ){	

	// Iterate over all instances of the uploader on the page 
	$('.wcv-img-id').each( function () {
	    
	    var id = this.id; 

	    // Handle Add banner
		$('.wcv-file-uploader-add' + id ).on( 'click', function(e) { 
			e.preventDefault(); 
			file_uploader( id ); 
			return false; 
		}); 

		$('.wcv-file-uploader-delete' + id ).on('click', function(e) { 
			e.preventDefault(); 
			// reset the data so that it can be removed and saved. 
			$( '.wcv-file-uploader' + id ).html(''); 
			$( 'input[id=' + id + ']').val(''); 
			$('.wcv-file-uploader-delete' + id ).addClass('hidden'); 
			$('.wcv-file-uploader-add' + id ).removeClass('hidden'); 
		});

	});

	function file_uploader( id )
	{

		var media_uploader, json;

		if (undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    media_uploader = wp.media({
      		title: $( '#' + id ).data('window_title'), 
      		button: {
        		text: $( '#' + id ).data('save_button'), 
      		},
      		multiple: false  // Set to true to allow multiple files to be selected
    	});

	    media_uploader.on( 'select' , function(){
	    	json = media_uploader.state().get('selection').first().toJSON(); 

	    	if ( 0 > $.trim( json.url.length ) ) {
		        return;
		    }

		    $( '.wcv-file-uploader' + id )
		    	.append( '<img src="'+ json.sizes.thumbnail.url + '" alt="' + json.caption + '" title="' + json.title +'" style="max-width: 100%;" />' ); 
		    
		    $('#' + id ).val( json.id ); 

		    $('.wcv-file-uploader-add' + id ).addClass('hidden'); 
		    $('.wcv-file-uploader-delete' + id ).removeClass('hidden'); 

	    });

	    media_uploader.open();
	}


	function shipping_address_other( ) { 

		var shipping_from   = $('#_wcv_shipping_from').val();

		if ( 'other' === shipping_from ) {
			$('.shipping_other').show(); 
		} else { 
			$('.shipping_other').hide(); 
		}

	}

	// Shipping from other address trigger 
	$( 'select#_wcv_shipping_from' ).change( function () {
		
		// Get value
		var select_val = $( this ).val();

		if ( 'other' === select_val ) {
			$('.shipping_other').show(); 
		} else { 
			$('.shipping_other').hide(); 
		}

	}).change();

	// Flat Rates 
	// National 
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
	// Toggle Free shipping 
	$( '#_wcv_shipping_fee_national_free' ).change(function() { enable_disable( $( this ), $( '#_wcv_shipping_fee_national' ) ); } ); 

	$( '#_wcv_shipping_fee_national_disable' ).change(function() { enable_disable( $( this ), $( '.wcv-disable-national-input' ) ); } ); 
	
	// International 
	// Free shipping 
	$( '#_wcv_shipping_fee_international_free' ).change(function() { enable_disable( $( this ), $( '#_wcv_shipping_fee_international' ) ); } ); 

	// Disable international shipping 
	$( '#_wcv_shipping_fee_international_disable' ).change(function() { enable_disable( $( this ), $( '.wcv-disable-international-input' ) ); } ); 
	
	shipping_address_other(); 
	
});

	