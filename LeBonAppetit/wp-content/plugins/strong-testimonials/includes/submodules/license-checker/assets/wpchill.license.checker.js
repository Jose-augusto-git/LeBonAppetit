jQuery( document ).ready( function ( $ ) {

	$( '.wpchill-already-extended' ).click( function () {

			var button = $( this );

			setTimeout( function () {
				button.html( 'Checking....' );
			}, 300 );

			$.ajax(
				{
					url     : ajaxurl,
					type    : 'post',
					async   : true,
					cache   : false,
					dataType: 'json',
					data    : {
						action: 'wpchill_check_license_valability',
						nonce : WPChill.nonce,
					},
					success : function () {
						location.reload();
					},
				}
			);
		}
	);
} );