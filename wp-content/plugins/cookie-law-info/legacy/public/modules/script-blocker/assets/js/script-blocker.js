(function ($) {
	'use strict';

	var CLIScriptBlocker = {
		set: function () {
			this.changeStatusEvent();
			this.changeCategory();
			this.disableEvents();
		},
		changeStatusEvent: function() {
			jQuery( '.wt-cli-plugin-status' ).on(
				'change',
				function (e) {
					e.preventDefault();
					CLIScriptBlocker.changeStatus( jQuery( this ) );
				}
			);
		},
		disableEvents: function(){
			jQuery( '.wt-cli-script-blocker-disabled' ).children().click( function(){return false;} );
			jQuery( '.wt-cli-plugin-inactive' ).children().click( function(){return false;} );
		},
		changeStatus: function( element ) {

			var script_id = element.attr( 'data-script-id' );
			var status    = element.is( ':checked' );

			var data = {
				'action'		            :   'wt_cli_change_plugin_status',
				'_wpnonce'                  :  	wt_cli_script_blocker_obj.nonce,
				'script_id'                 :   script_id,
				'status'                    :   status
			};
			jQuery.ajax(
				{
					url: wt_cli_script_blocker_obj.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function (response) {
						if (response.success === true) {
							cli_notify_msg.success( wt_cli_script_blocker_obj.messages.success );
						}
					},
					error: function () {
						cli_notify_msg.error( wt_cli_script_blocker_obj.messages.success );
					}
				}
			);
		},
		changeCategory: function () {
			jQuery( '[name="cliscript_category"]' ).on(
				'change',
				function (e) {

					e.preventDefault();
					var element   = jQuery( this );
					var script_id = element.closest( 'tr' ).attr( 'data-script-id' );
					var category  = this.value;
					console.log( category );
					var data = {
						'action': 'cli_change_script_category',
						'_wpnonce': wt_cli_script_blocker_obj.nonce,
						'script_id': script_id,
						'category': category
					};
					jQuery.ajax(
						{
							url: wt_cli_script_blocker_obj.ajax_url,
							type: 'POST',
							data: data,
							dataType: 'json',
							success: function (response) {
								if (response.success === true) {
									cli_notify_msg.success( wt_cli_script_blocker_obj.messages.success );
								} else {
									cli_notify_msg.error( response.data );
								}
							},
							error: function () {
								cli_notify_msg.error( wt_cli_script_blocker_obj.messages.error );
							}
						}
					);
				}
			);

		}
	}
	jQuery( document ).ready(
		function () {
			CLIScriptBlocker.set();
		}
	);

})( jQuery );
