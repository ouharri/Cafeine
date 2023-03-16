wp.customize.controlConstructor['blossom-recipe-slider'] = wp.customize.Control.extend({
	ready: function(){
		'use strict';

		var control = this,
		    value,
		    thisInput,
		    inputDefault,
		    changeAction;

		// Update the text value
		jQuery(document).on('input change', 'input[type=range]', function() {
			jQuery( this ).closest( 'label' ).find( '.range_value .value' ).html(jQuery(this).val());
		});

		// Handle the reset button
		jQuery( '.slider-reset' ).on( 'click', function() {
			thisInput    = jQuery( this ).closest( 'label' ).find( 'input' );
			inputDefault = thisInput.data( 'reset_value' );
			thisInput.val( inputDefault );
			thisInput.change();
			jQuery( this ).closest( 'label' ).find( '.range_value .value' ).text( inputDefault );
		});

		if ( 'postMessage' === control.setting.transport ) {
			changeAction = 'mousemove change';
		} else {
			changeAction = 'change';
		}

		// Save changes.
		this.container.on( changeAction, 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});
	}
});