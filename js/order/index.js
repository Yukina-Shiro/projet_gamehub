var order = {
	init : function() {
		let collection = document.getElementsByClassName( 'auto_submit');
		Array.prototype.slice.call( collection).forEach( function( element) {
			element.addEventListener( 'change', ( event) => {
				document.getElementById( 'order_create').submit();
			});
			console.log( element.id);
		});
	}
}