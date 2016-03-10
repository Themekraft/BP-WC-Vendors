jQuery( function( $ ){	

	 jQuery('a.wcv_order_note').on('click', function (e) {
        e.preventDefault();
        var order_note = 'add_note_' + $(this).data('order_id'); 
        $('.add_note_' + $(this).data('order_id')).slideToggle(); 
    });

}); 