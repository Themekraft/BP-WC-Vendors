(function( $ ) {
	'use strict';

	/**
	 * Code required to create the charts 
	 */

	 $( window ).load(function() {
		 
		// Only run on dashboard page
	 	if ( typeof orders_chart_label !== 'undefined' ) {
			var orderdata = {
			    labels: orders_chart_label,
			    datasets: [
			        {
			            label: "My First dataset",
			            fillColor: "rgba(220,220,220,0.5)",
			            strokeColor: "rgba(220,220,220,0.8)",
			            highlightFill: "rgba(220,220,220,0.75)",
			            highlightStroke: "rgba(220,220,220,1)",
			            data: orders_chart_data,
			        }
			    ]
			};

			var orders_chart_canvas = document.getElementById( "orders_chart" ).getContext( "2d" );
			var ordersBarChart = new Chart( orders_chart_canvas ).Bar( orderdata, { responsive : true } );
		
		} 
		
		// Only run on dashboard page
		if ( typeof pieData !== 'undefined' ) {

			var products_chart_canvas = document.getElementById( "products_chart" ).getContext( "2d" );
			window.myPie = new Chart( products_chart_canvas ).Pie( pieData, { responsive : true} );
		} 

	});

})( jQuery );
