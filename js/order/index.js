// Javascript for Order
var order = {
	init : function() {
		console.log( 'In order.init()');
		// Get the first form with id  in { #order_create, #order_read, #order_update, #order_delete}
		//let order = document.querySelector( '#order_create, #order_read, #order_update, #order_delete');
		// Get the first id that begins by « order_ »
		let order = document.querySelector( '[id^=order_]');
		if ( ( order !== undefined) && ( order !== null) ) {
			console.log( ' Selected order : ' +  order.id);
			// Get all elements with class « auto_submit »
			let collection = document.getElementsByClassName( 'auto_submit');
			// Loop over the elements collection
			Array.from( collection).forEach( function( element) {
				// Submit the form on « change » event for all the elements
				element.addEventListener( 'change', ( event) => {
					order.submit();
				});
				console.log(  ' - ' + element.id);
			});
		}
	}
}