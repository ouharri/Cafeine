/* global importer_endpoint, console */

/**
 * Template Directory Customizer Admin Dashboard Script
 *
 * This handles the template directory.
 *
 * @since    1.0.0
 * @package mods/template-directory/js
 *
 * @author    SktThemes
 */

var sktb_template_directory = function ( $ ) {
	'use strict';

	$(
		function () {

			// Handle import click.
			$( '.wp-full-overlay-header' ).on(
				'click', '.sktb-import-template', function () {
					$( this ).addClass( 'sktb-import-queue updating-message sktb-updating' ).html( '' );
					$( '.sktb-template-preview .close-full-overlay, .sktb-next-prev' ).remove();
					var template_url = $( this ).data( 'template-file' );
					var template_name = $( this ).data( 'template-title' );
					if ( $( '.active .sktb-installable' ).length || $( '.active .sktb-activate' ).length ) {
						checkAndInstallPlugins();
					} else {
						$.ajax(
							{
								url: importer_endpoint.url,
								beforeSend: function ( xhr ) {
									$( '.sktb-import-queue' ).addClass( 'sktb-updating' ).html( '' );
									xhr.setRequestHeader( 'X-WP-Nonce', importer_endpoint.nonce );
								},
								data: {
									template_url: template_url,
									template_name: template_name
								},
								type: 'POST',
								success: function ( data ) {
									$( '.sktb-updating' ).replaceWith( '<span class="sktb-done-import"><i class="dashicons-yes dashicons"></i></span>' );
									location.href = data;
								},
								error: function ( error ) {
									console.error( error );
								},
								complete: function () {
									$( '.sktb-updating' ).replaceWith( '<span class="sktb-done-import"><i class="dashicons-yes dashicons"></i></span>' );
								}
							}, 'json'
						);
					}
				}
			);

			$( '#sktb-template-dir-fetch-templates' ).on( 'click', function ( e ) {
				e.preventDefault();
				$.ajax(
					{
                        url: importer_endpoint.fetch_templates_url,
                        beforeSend: function ( xhr ) {
                            $( '#sktb-template-dir-fetch-templates .dashicons' ).hide();
                            $( '#sktb-template-dir-fetch-templates' ).addClass( 'updating-message' );
                            xhr.setRequestHeader( 'X-WP-Nonce', importer_endpoint.nonce );
                        },
                        data: {
                            plugin_slug: importer_endpoint.plugin_slug,
                        },
                        type: 'POST',
                        success: function () {
                            $( '#sktb-template-dir-fetch-templates' ).removeClass( 'updating-message' ).attr('disabled', 'true').empty().html('<i class="dashicons-yes dashicons" style="margin-right:0"></i>');
                            location.reload();
						},
                        error: function( error ) {
                            console.log(error);
                        }
					}, 'json'
				);
			} );

			function checkAndInstallPlugins() {
				var installable = $( '.active .sktb-installable' );
				var toActivate = $( '.active .sktb-activate' );
				if ( installable.length || toActivate.length ) {

					$( installable ).each(
						function () {
							var plugin = $( this );
							$( plugin ).removeClass( 'sktb-installable' ).addClass( 'sktb-installing' );
							$( plugin ).find( 'span.dashicons' ).replaceWith( '<span class="dashicons dashicons-update" style="-webkit-animation: rotation 2s infinite linear; animation: rotation 2s infinite linear; color: #ffb227 "></span>' );
							var slug = $( this ).find( '.sktb-install-plugin' ).attr( 'data-slug' );
							wp.updates.installPlugin(
								{
									slug: slug,
									success: function ( response ) {
										activatePlugin( response.activateUrl, plugin );
									}
								}
							);
						}
					);

					$( toActivate ).each(
						function () {
							var plugin = $( this );
							var activateUrl = $( plugin ).find( '.activate-now' ).attr( 'href' );
							if ( typeof activateUrl !== 'undefined' ) {
								activatePlugin( activateUrl, plugin );
							}
						}
					);
				}
			}

			function activatePlugin( activationUrl, plugin ) {
				$.ajax(
					{
						type: 'GET',
						url: activationUrl,
						beforeSend: function () {
							$( plugin ).removeClass( 'sktb-activate' ).addClass( 'sktb-installing' );
							$( plugin ).find( 'span.dashicons' ).replaceWith( '<span class="dashicons dashicons-update" style="-webkit-animation: rotation 2s infinite linear; animation: rotation 2s infinite linear; color: #ffb227 "></span>' );
						},
						success: function () {
							$( plugin ).find( '.dashicons' ).replaceWith( '<span class="dashicons dashicons-yes" style="color: #34a85e"></span>' );
							$( plugin ).removeClass( 'sktb-installing' );
						},
						complete: function () {
							if ( $( '.active .sktb-installing' ).length === 0 ) {
								$( '.sktb-import-queue' ).trigger( 'click' );
							}
						}
					}
				);
			}

			// Handle sidebar collapse in preview.
			$( '.sktb-template-preview' ).on(
				'click', '.collapse-sidebar', function (event) {
					event.preventDefault();
					var overlay = $( '.sktb-template-preview' );
					if ( overlay.hasClass( 'expanded' ) ) {
						overlay.removeClass( 'expanded' );
						overlay.addClass( 'collapsed' );
						return false;
					}

					if ( overlay.hasClass( 'collapsed' ) ) {
						overlay.removeClass( 'collapsed' );
						overlay.addClass( 'expanded' );
						return false;
					}
				}
			);

			// Handle responsive buttons.
			$( '.sktb-responsive-preview' ).on(
				'click', 'button', function () {
					$( '.sktb-template-preview' ).removeClass( 'preview-mobile preview-tablet preview-desktop' );
					var deviceClass = 'preview-' + $( this ).data( 'device' );
					$( '.sktb-responsive-preview button' ).each(
						function () {
							$( this ).attr( 'aria-pressed', 'false' );
							$( this ).removeClass( 'active' );
						}
					);

					$( '.sktb-responsive-preview' ).removeClass( $( this ).attr( 'class' ).split( ' ' ).pop() );
					$( '.sktb-template-preview' ).addClass( deviceClass );
					$( this ).addClass( 'active' );
				}
			);

			// Hide preview.
			$( '.close-full-overlay' ).on(
				'click', function () {
					$( '.sktb-template-preview .sktb-theme-info.active' ).removeClass( 'active' );
					$( '.sktb-template-preview' ).hide();
					$( '.sktb-template-frame' ).attr( 'src', '' );
				}
			);

			// Open preview routine.
			$( '.sktb-preview-template' ).on(
				'click', function () {
					var templateSlug = $( this ).data( 'template-slug' );
					var previewUrl = $( this ).data( 'demo-url' );
					$( '.sktb-template-frame' ).attr( 'src', previewUrl );
					$( '.sktb-theme-info.' + templateSlug ).addClass( 'active' );
					setupImportButton();
					$( '.sktb-template-preview' ).fadeIn();
				}
			);

			// Handle left-right navigation between templates.
			$( '.sktb-next-prev .next-theme' ).on(
				'click', function () {
					var active = $( '.sktb-theme-info.active' ).removeClass( 'active' );
					if ( active.next() && active.next().length ) {
						active.next().addClass( 'active' );
					} else {
						active.siblings( ':first' ).addClass( 'active' );
					}
					changePreviewSource();
					setupImportButton();
				}
			);
			$( '.sktb-next-prev .previous-theme' ).on(
				'click', function () {
					var active = $( '.sktb-theme-info.active' ).removeClass( 'active' );
					if ( active.prev() && active.prev().length ) {
						active.prev().addClass( 'active' );
					} else {
						active.siblings( ':last' ).addClass( 'active' );
					}
					changePreviewSource();
					setupImportButton();
				}
			);

			// Change preview source.
			function changePreviewSource() {
				var previewUrl = $( '.sktb-theme-info.active' ).data( 'demo-url' );
				$( '.sktb-template-frame' ).attr( 'src', previewUrl );
			}

			function setupImportButton() {
				var button = $( '.wp-full-overlay-header .sktb-import-template' );
				var dataUpsell = $( '.active' ).data( 'upsell' );
				var upsellButton = $( '.sktb-upsell-button' );
				if ( dataUpsell === 'yes' ) {
					$( button ).hide();
					$( upsellButton ).show();
					return false;
				}
				$( button ).show();
				$( upsellButton ).hide();
				var installable = $( '.active .sktb-installable' );
				if ( installable.length > 0 ) {
					$( '.wp-full-overlay-header .sktb-import-template' ).text( 'Install and Import' );
				} else {
					$( '.wp-full-overlay-header .sktb-import-template' ).text( 'Import' );
				}
				var activeTheme = $( '.sktb-theme-info.active' );
				$( button ).attr( 'data-template-file', $( activeTheme ).data( 'template-file' ) );
				$( button ).attr( 'data-template-title', $( activeTheme ).data( 'template-title' ) );
			}
		}
	);
};

sktb_template_directory( jQuery );
