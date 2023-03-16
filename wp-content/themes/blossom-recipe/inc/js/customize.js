jQuery(document).ready(function($) {
	/* Move Front page widgets to front-page panel */
	wp.customize.section( 'sidebar-widgets-newsletter-section' ).panel( 'general_settings' );
    wp.customize.section( 'sidebar-widgets-newsletter-section' ).priority( '60' );
 	
 	$( 'input[name=blossom-recipe-flush-local-fonts-button]' ).on( 'click', function( e ) {
        var data = {
            wp_customize: 'on',
            action: 'blossom_recipe_flush_fonts_folder',
            nonce: blossom_recipe_cdata.flushFonts
        };  
        $( 'input[name=blossom-recipe-flush-local-fonts-button]' ).attr('disabled', 'disabled');

        $.post( ajaxurl, data, function ( response ) {
            if ( response && response.success ) {
                $( 'input[name=blossom-recipe-flush-local-fonts-button]' ).val( 'Successfully Flushed' );
            } else {
                $( 'input[name=blossom-recipe-flush-local-fonts-button]' ).val( 'Failed, Reload Page and Try Again' );
            }
        });
    });

});

( function( api ) {

	// Extends our custom "example-1" section.
	api.sectionConstructor['blossom-recipe-pro-section'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );