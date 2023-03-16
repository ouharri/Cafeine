jQuery(document).ready(function ($) {

	// Tabs
	$( ".inline-list" ).each( function() {
		$( this ).find( "li" ).each( function(i) {
			$( this).on( 'click', function(){
				$( this ).addClass( "current" ).siblings().removeClass( "current" )
				.parents( "#wpbody" ).find( "div.panel-left" ).removeClass( "visible" ).end().find( 'div.panel-left:eq('+i+')' ).addClass( "visible" );
				return false;
			} );
		} );
	} );

	//faq toggle
	$('.toggle-block:not(.active) .toggle-content').hide();
	$('.toggle-block .toggle-title').on( 'click', function(){
		$(this).parent('.toggle-block').siblings().removeClass('active');
		$(this).parent('.toggle-block').addClass('active');
		$(this).parent('.toggle-block').siblings().children('.toggle-content').slideUp();
		$(this).siblings('.toggle-content').slideDown();
	});
});