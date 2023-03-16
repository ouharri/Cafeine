(function($) {
	'use strict';
	var ckyes = {
		errorMessage: '',
		set: function() {
			this.events();
			this.errorMessage = ckyes_admin.messages.error;
		},
		events: function() {

			jQuery( document ).on(
				'click',
				'#wt-cli-ckyes-register-btn',
				function(event) {
					event.preventDefault();
					ckyes.register( jQuery( this ) );
				}
			);
			jQuery( document ).on(
				'click',
				'#wt-cli-ckyes-login-btn',
				function(event) {
					event.preventDefault();
					ckyes.login( jQuery( this ) );
				}
			);
			jQuery( document ).on(
				'click',
				'#wt-cli-ckyes-pwd-reset-link',
				function(event) {
					event.preventDefault();
					ckyes.resetPassword( jQuery( this ) );
				}
			);
			jQuery( document ).on(
				'click',
				'#wt-cli-ckyes-password-reset-btn',
				function(event) {
					event.preventDefault();
					ckyes.resetPassword( jQuery( this ) );
				}
			);
			jQuery( document ).on(
				'click',
				'.wt-cli-ckyes-account-action',
				function(event) {
					event.preventDefault();
					ckyes.accountActions( jQuery( this ) );
				}
			);
			jQuery( document ).on(
				'click',
				'#wt-cli-ckyes-email-resend-link',
				function(event) {
					event.preventDefault();
					ckyes.resendEmail( jQuery( this ) );
				}
			);
			jQuery( document ).on(
				'click',
				'.wt-cli-ckyes-delete-btn',
				function(event) {
					event.preventDefault();
					ckyes.deleteAccount( jQuery( this ) );
				}
			);
		},
		register: function( element ) {
			wtCliAdminFunctions.loadSpinner( element );
			var form  = element.closest( 'form' );
			var email = form.find( 'input[name="ckyes-email"]' ).val();
			var data  = {
				'action': 'cookieyes_ajax_main_controller',
				'sub_action': 'register',
				'_wpnonce': ckyes_admin.nonce,
				'email': email,
			};
			jQuery.ajax(
				{
					url: ckyes_admin.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function(response) {
						var data = response.data;
						var msg  = (data.message ? data.message : '');
						wtCliAdminFunctions.removeSpinner( element );
						if (response.success === true) {
							if ( data.html ) {
								if ( data.html ) {
									wtCliAdminFunctions.createModal( '',data.html );
									jQuery( document ).trigger( "trggerReloadScanner" );
								}
							}
						} else {
							if ( data.code && data.code === 102 ) {
								if ( data.html ) {
									wtCliAdminFunctions.createModal( '',data.html );
								}
							} else {
								if ( data.message ) {
									wtCliAdminFunctions.createModal( '',data.message );
									setTimeout(
										function(){
											window.location.reload();
										},
										1500
									);
								}
							}
						}
					},
					error: function() {
						wtCliAdminFunctions.createModal( '',ckyes.errorMessage );
					}
				}
			);
		},
		login: function(element) {
			wtCliAdminFunctions.loadSpinner( element );
			var form     = element.closest( 'form' );
			var formRow  = form.find( '.wt-cli-form-row' );
			var email    = form.find( 'input[name="ckyes-email"]' ).val();
			var password = form.find( 'input[name="ckyes-password"]' ).val();

			var data = {
				'action': 'cookieyes_ajax_main_controller',
				'sub_action': 'login',
				'_wpnonce': ckyes_admin.nonce,
				'email': email,
				'password': password
			};
			jQuery.ajax(
				{
					url: ckyes_admin.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function(response) {
						var data = response.data;
						var msg  = (data.message ? data.message : '');
						wtCliAdminFunctions.removeSpinner( element );
						if (response.success === true) {
							wtCliAdminFunctions.createModal( '', msg );
							setTimeout(
								function() {
									window.location.reload();
								},
								1500
							);
						} else {
							wtCliAdminFunctions.addInlineMessage( msg, 'error', formRow );
						}
					},
					error: function() {
						wtCliAdminFunctions.createModal( '',ckyes.errorMessage );
					}
				}
			);
		},
		resetPassword: function(element) {
			wtCliAdminFunctions.loadSpinner( element );
			var form  = element.closest( 'form' );
			var email = form.find( 'input[name="ckyes-email"]' ).val();
			var data  = {
				'action': 'cookieyes_ajax_main_controller',
				'sub_action': 'reset_password',
				'_wpnonce': ckyes_admin.nonce,
				'email': email,
			};
			jQuery.ajax(
				{
					url: ckyes_admin.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function(response) {
						var data = response.data;
						var msg  = (data.message ? data.message : '');
						wtCliAdminFunctions.removeSpinner( element );
						if ( true === data.status && 202 === data.code ) {
							wtCliAdminFunctions.addInlineMessage( msg, 'success', form );
						} else {
							wtCliAdminFunctions.addInlineMessage( msg, 'notice', form );
						}
					},
					error: function() {
						wtCliAdminFunctions.createModal( '', ckyes.errorMessage );
					}
				}
			);
		},
		accountActions:function( element ){
			wtCliAdminFunctions.loadSpinner( element );
			var action = element.attr( 'data-action' );
			var data   = {
				'action': 'cookieyes_ajax_main_controller',
				'sub_action': 'connect_disconnect',
				'account_action': action,
				'_wpnonce': ckyes_admin.nonce,
			};
			jQuery.ajax(
				{
					url: ckyes_admin.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function(response) {
						var data = response.data;
						var msg  = (data.message ? data.message : '');
						wtCliAdminFunctions.removeSpinner( element );
						wtCliAdminFunctions.createModal( '', msg );
						setTimeout(
							function() {
								window.location.reload();
							},
							2000
						);
					},
					error: function() {
						wtCliAdminFunctions.createModal( '', ckyes.errorMessage );
					}
				}
			);
		},
		resendEmail: function() {
			var email = "";
			var data  = {
				'action': 'cookieyes_ajax_main_controller',
				'sub_action': 'resend_email',
				'_wpnonce': ckyes_admin.nonce,
				'email': email,
			};
			jQuery.ajax(
				{
					url: ckyes_admin.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function( response ) {
						var data = response.data;
						if ( response.success === true ) {
							if ( data.html ) {
								wtCliAdminFunctions.createModal( '', data.html );
							}
						} else {
							if ( data.message ) {
								wtCliAdminFunctions.createModal( '', data.message );
							}
						}
					},
					error: function() {
						wtCliAdminFunctions.createModal( '', ckyes.errorMessage );
					}
				}
			);
		},
		deleteAccount: function( element ){
			var action = element.attr( 'data-action' );
			console.log( action );
			if ( 'delete-account' === action ) {
				this.sendDeleteRequest( element );
			} else {
				wtCliAdminFunctions.showModal( 'wt-cli-ckyes-modal-delete-account' );
			}
		},
		sendDeleteRequest: function( element ){
			wtCliAdminFunctions.loadSpinner( element );
			var data = {
				'action': 'cookieyes_ajax_main_controller',
				'sub_action': 'delete_account',
				'_wpnonce': ckyes_admin.nonce,
			};
			jQuery.ajax(
				{
					url: ckyes_admin.ajax_url,
					type: 'POST',
					data: data,
					dataType: 'json',
					success: function( response ) {
						var data = response.data;
						wtCliAdminFunctions.removeSpinner( element );
						if ( response.success === true ) {
							wtCliAdminFunctions.createModal( '', ckyes_admin.messages.delete_success );
							setTimeout(
								function() {
									window.location.reload();
								},
								1500
							);
						} else {
							wtCliAdminFunctions.createModal( '', ckyes_admin.messages.delete_failed );
						}
					},
					error: function() {
						wtCliAdminFunctions.createModal( '', ckyes_admin.messages.delete_failed );
					}
				}
			);
		}

	}
	jQuery( document ).ready(
		function() {
			ckyes.set();
		}
	);

})( jQuery );
