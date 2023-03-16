(function( $ ) {
	'use strict';
	$(
		function() {
			var CLI_cookie_scanner = {
				continue_scan:1,
				abortingScan:0,
				onPrg:0,
				Set:function()
			{
					jQuery( document ).on(
						'click',
						'#wt-cli-ckyes-scan',
						function(){
							CLI_cookie_scanner.continue_scan = 1;
							CLI_cookie_scanner.doScan();
						}
					);
					jQuery( document ).on(
						'click',
						'#wt-cli-ckyes-connect-scan',
						function(){
							CLI_cookie_scanner.continue_scan = 1;
							CLI_cookie_scanner.connectScan( jQuery( this ) );
						}
					);
					jQuery( document ).on(
						'click',
						'#wt-cli-cookie-scan-abort',
						function( event ) {
							event.preventDefault();
							CLI_cookie_scanner.abortScan( jQuery( this ) );
						}
					);
					jQuery( document ).on(
						'click',
						'.wt-cli-cookie-scan-preview-modal',
						function( event ) {
							event.preventDefault();
							wtCliAdminFunctions.showModal( 'wt-cli-ckyes-modal-settings-preview' );
						}
					);
					this.reloadScanner();
					this.attachScanImport();
					this.checkScanStatus();
				},
				doScan:function()
			{
					CLI_cookie_scanner.nextScanID();
				},
				scanAgain:function()
			{
					$( '.cli_scan_again' ).unbind( 'click' ).click(
						function(){
							CLI_cookie_scanner.continue_scan = 1;
							CLI_cookie_scanner.nextScanID();
						}
					);
				},
				scanNow:function()
			{
					this.takePages();
				},
				takePages:function()
			{
					var data    = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'get_pages',
					};
					var scanbar = $( '.wt-cli-cookie-scan-bar' );
					scanbar.html( '<span style="float:left; height:40px; line-height:40px;">' + cookielawinfo_cookie_scaner.labels.finding + '</span> <img src="' + cookielawinfo_cookie_scaner.loading_gif + '" style="display:inline-block;" />' );
					$.ajax(
						{
							url: cookielawinfo_cookie_scaner.ajax_url,
							data: data,
							dataType: 'json',
							type: 'POST',
							success: function (data)
						{
								CLI_cookie_scanner.scan_id = typeof data.scan_id != 'undefined' ? data.scan_id : 0;
								if ( true === data.status ) {
									CLI_cookie_scanner.bulkScan( data.scan_id,data.total );
								} else {
									wtCliAdminFunctions.createModal( '',cookielawinfo_cookie_scaner.labels.page_fetch_error );
									CLI_cookie_scanner.reloadAfterRequest();
								}
							},
							error:function()
						{
								wtCliAdminFunctions.createModal( '',cookielawinfo_cookie_scaner.labels.page_fetch_error );
								CLI_cookie_scanner.reloadAfterRequest();
							}
						}
					);
				},
				attachScanImport: function (scan_id) {
					$( '.cli_import' ).unbind( 'click' ).click(
						function () {

							var scan_id = $( this ).attr( 'data-scan-id' );
							var html    = '<div id="wt-cli-cookie-scan-import" class="wt-cli-modal"><span class="wt-cli-modal-js-close">×</span>';
							html       += '<div class="wt-cli-modal-header"><h2>' + cookielawinfo_cookie_scaner.labels.import_options + '</h2></div>';
							html       += '<div class="wt-cli-modal-body">';
							html       += '<input type="radio" name="cli_import_options" id="cli_import_options_replace" value="1" /><label for="cli_import_options_replace"> ' + cookielawinfo_cookie_scaner.labels.replace_old + '</label><br />';
							html       += '<input type="radio" name="cli_import_options" id="cli_import_options_merge" value="2" checked /><label for="cli_import_options_merge"> ' + cookielawinfo_cookie_scaner.labels.merge + ' (' + cookielawinfo_cookie_scaner.labels.recommended + ')</label> <br />';
							html       += '<input type="radio" name="cli_import_options" id="cli_import_options_append" value="3" /><label for="cli_import_options_append"> ' + cookielawinfo_cookie_scaner.labels.append + ' (' + cookielawinfo_cookie_scaner.labels.not_recommended + ')</label> <br /><br />';
							html       += '<a class="button-secondary pull-left cli_import_cancel">' + cookielawinfo_cookie_scaner.labels.cancel + '</a>';
							html       += '<a class="button-primary pull-left cli_import_now" data-scan-id="' + scan_id + '" style="margin-left:5px;">' + cookielawinfo_cookie_scaner.labels.start_import + '</a>';
							html       += '</div>';
							html       += '</div>';

							if ($( '#wt-cli-cookie-scan-import' ).length === 0) {
								$( 'body' ).append( html );
								wtCliAdminFunctions.showModal( 'wt-cli-cookie-scan-import' );
								$( '.cli_import_cancel' ).click(
									function () {
										wtCliAdminFunctions.closeModal();
									}
								);
								$( '.cli_import_now' ).click(
									function () {
										wtCliAdminFunctions.loadSpinner( jQuery( this ) );
										var import_option = $( '[name="cli_import_options"]:checked' ).val();
										var scan_id       = $( this ).attr( 'data-scan-id' );
										CLI_cookie_scanner.importNow( scan_id, import_option );
									}
								);
							} else {
								wtCliAdminFunctions.showModal( 'wt-cli-cookie-scan-import' );
							}
						}
					);

				},
				importNow:function(scan_id,import_option)
			{
					if (this.onPrg == 1) {
						return false;
					}
					var data = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'import_now',
						scan_id:scan_id,
						import_option:import_option
					};
					$( '.wrap a' ).css( {'opacity':.5} );
					$( '.cli_import' ).html( cookielawinfo_cookie_scaner.labels.importing );
					$( '.cli_progress_action_main' ).html( cookielawinfo_cookie_scaner.labels.importing );
					$( '.spinner' ).css( {'visibility':'visible'} );
					this.onPrg = 1;
					$.ajax(
						{
							url:cookielawinfo_cookie_scaner.ajax_url,
							data:data,
							dataType:'json',
							type:'POST',
							success:function(data)
						{
								CLI_cookie_scanner.onPrg = 0;
								$( '.wrap a' ).css( {'opacity':1} );
								$( '.cli_import' ).html( cookielawinfo_cookie_scaner.labels.import );
								$( '.cli_progress_action_main' ).html( cookielawinfo_cookie_scaner.labels.import_finished );
								$( '.spinner' ).css( {'visibility':'hidden'} );
								wtCliAdminFunctions.closeModal();

								if (data.response === true) {
									cli_notify_msg.success( data.message );
								} else {
									cli_notify_msg.error( data.message );
								}
							},
							error:function()
						{
								CLI_cookie_scanner.onPrg = 0;
								$( '.wrap a' ).css( {'opacity':1} );
								$( '.cli_import' ).html( cookielawinfo_cookie_scaner.labels.import );
								$( '.cli_progress_action_main' ).html( cookielawinfo_cookie_scaner.labels.error );
								$( '.spinner' ).css( {'visibility':'hidden'} );
								cli_notify_msg.error( cookielawinfo_cookie_scaner.labels.error );
							}
						}
					);
				},
				connectScan: function( element ){

					var data = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'connect_scan',
					};
					wtCliAdminFunctions.loadSpinner( element );
					$.ajax(
						{
							url: cookielawinfo_cookie_scaner.ajax_url,
							data: data,
							dataType: 'json',
							type: 'POST',
							success:function( response )
						{
									wtCliAdminFunctions.removeSpinner( element );
									var data = response.data;
								if ( response.success === true ) {
									CLI_cookie_scanner.doScan();
								} else {
									if ( data.code ) {
										if ( data.code == 102 || data.code == 107 ) {
											if ( data.html ) {
												wtCliAdminFunctions.createModal( '',data.html );
											}
										} else if ( data.code == 108 ) {

											wtCliAdminFunctions.showModal( 'wt-cli-ckyes-modal-register' );

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
								}
							},
							error:function()
						{
								wtCliAdminFunctions.createModal( '','Invalid request' );
							}
						}
					);
				},
				nextScanID: function(){
					var data    = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'next_scan_id',
					};
					var scanBtn = jQuery( '#wt-cli-ckyes-scan' );
					wtCliAdminFunctions.loadSpinner( scanBtn );
					$.ajax(
						{
							url: cookielawinfo_cookie_scaner.ajax_url,
							data: data,
							dataType: 'json',
							type: 'POST',
							success:function( response )
						{
								var data = response.data;
								wtCliAdminFunctions.removeSpinner( scanBtn );
								if ( response.success === true ) {
									CLI_cookie_scanner.scanNow();
								} else if ( data.code == 107 ) {

									if ( data.html ) {
										wtCliAdminFunctions.createModal( '',data.html );
									}
								} else {
									if ( data.message ) {
										wtCliAdminFunctions.createModal( '',data.message );
										CLI_cookie_scanner.reloadAfterRequest();
									}
								}
							},
							error:function()
						{
								wtCliAdminFunctions.createModal( '','Invalid request' );
								wtCliAdminFunctions.removeSpinner( scanBtn );
							}
						}
					);
				},
				reloadAfterRequest: function( duration ) {
					var timeout = 2000;
					if (typeof duration !== 'undefined') {
						timeout = duration;
					}
					setTimeout(
						function(){
							window.location.reload();
						},
						timeout
					);
				},
				bulkScan: function(scan_id,total) {

					var data = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'bulk_scan',
						scan_id:scan_id,
						total:total
					};
					$.ajax(
						{
							url: cookielawinfo_cookie_scaner.ajax_url,
							data: data,
							dataType: 'json',
							type: 'POST',
							success:function(response)
						{
								var data = response.data;
								if ( response.success === true ) {
									wtCliAdminFunctions.createModal( data.title,data.message );
									if ( data.html ) {
										jQuery( '.wt-cli-cookie-scan-bar' ).html( data.html );
									}
								} else {
									if ( data.message ) {
										wtCliAdminFunctions.createModal( '',data.message );
										CLI_cookie_scanner.reloadAfterRequest( 3000 );
									}
								}
							},
							error:function()
						{
								wtCliAdminFunctions.createModal( '',data.message );
								CLI_cookie_scanner.reloadAfterRequest( 6000 );
							}
						}
					);
				},
				checkScanStatus: function(){
					var scanStatus = Boolean( cookielawinfo_cookie_scaner.scan_status );
					if ( scanStatus === true ) {

						var data = {
							action: 'cli_cookie_scaner',
							security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
							cli_scaner_action:'check_status',
						};
						$.ajax(
							{
								url: cookielawinfo_cookie_scaner.ajax_url,
								data: data,
								dataType: 'json',
								type: 'POST',
								success:function(response)
							{

									if ( response.success === true ) {
										CLI_cookie_scanner.fetchResult();
									} else {
										if ( response.data.refresh === true ) {
											CLI_cookie_scanner.reloadAfterRequest( 10 );
										}
									}

								},
								error:function()
							{
								}
							}
						);
					}

				},
				fetchResult: function(){
					var data = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'fetch_result',
					};
					$.ajax(
						{
							url: cookielawinfo_cookie_scaner.ajax_url,
							data: data,
							dataType: 'json',
							type: 'POST',
							success:function(response)
						{
								if ( response.success === true ) {
									CLI_cookie_scanner.reloadAfterRequest( 10 );
								}
							},
							error:function()
						{

							}
						}
					);
				},
				abortScan: function( element ){

					if ( CLI_cookie_scanner.abortingScan === 1 ) {
						return false;
					}
					var scanner = jQuery( '.wt-cli-scan-status-bar' );
					scanner.html( cookielawinfo_cookie_scaner.labels.abort );
					scanner.css( {'color':'#444444'} );
					wtCliAdminFunctions.loadSpinner( scanner );
					CLI_cookie_scanner.abortingScan = 1;
					var data                        = {
						action: 'cli_cookie_scaner',
						security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
						cli_scaner_action:'stop_scan',
					};
					$.ajax(
						{
							url: cookielawinfo_cookie_scaner.ajax_url,
							data: data,
							dataType: 'json',
							type: 'POST',
							success:function(response)
						{
								if ( response.success === true ) {
									scanner.css( {'color':'#2fab10'} );
								} else {
									scanner.css( {'color':'#f44336'} );
								}
								if ( response.data.message ) {
									scanner.html( response.data.message );
								}
								CLI_cookie_scanner.reloadAfterRequest();

							},
							error:function()
						{
								wtCliAdminFunctions.createModal( '', cookielawinfo_cookie_scaner.labels.abort_failed );
								CLI_cookie_scanner.reloadAfterRequest();
							}
						}
					);
				},
				reloadScanner: function(){
					$( document ).on(
						"trggerReloadScanner",
						function(){
							var data = {
								action: 'cli_cookie_scaner',
								security: cookielawinfo_cookie_scaner.nonces.cli_cookie_scaner,
								cli_scaner_action:'get_scan_html',
							};
							$.ajax(
								{
									url: cookielawinfo_cookie_scaner.ajax_url,
									data: data,
									dataType: 'json',
									type: 'POST',
									success:function( response )
								{
										var data = response.data;
										if ( response.success === true ) {
											if ( data.scan_html ) {
												$( '.wt-cli-cookie-scan-notice' ).html( data.scan_html );
											}
										}
									},
									error:function()
								{

									}
								}
							);
						}
					);
				}
			}

			jQuery( document ).ready(
				function(){
					CLI_cookie_scanner.Set();
				}
			);
		}
	);
})( jQuery );
