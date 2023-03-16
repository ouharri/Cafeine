jQuery(
	function($) {

		var ccpaEnabledState = jQuery( 'input[name="ccpa_enabled_field"]:checked' ).val();
		function changeMessage( $event = false ) {
			  var message             = '';
			  var toggler             = jQuery( 'input[type="radio"][name="consent_type_field"]:checked' );
			  var consentType         = toggler.val();
			  var ccpaSettingsEnabled = false;
			  var gdprEnabled         = true;
			  var toggleTarget        = toggler.attr( 'data-cli-toggle-target' );

			  jQuery( '.wt-cli-section-gdpr-ccpa .wt-cli-section-inner' ).hide();
			  jQuery( '.wt-cli-toggle-content' ).hide();

			if (consentType == 'ccpa') {
				message             = jQuery( '#wt_ci_ccpa_only' ).val();
				ccpaSettingsEnabled = true;
				gdprEnabled         = false;
			} else if ( consentType == 'ccpa_gdpr') {
				message = jQuery( '#wt_ci_ccpa_gdpr' ).val();
				jQuery( '.wt-cli-section-gdpr-ccpa .wt-cli-section-inner' ).show();
				ccpaSettingsEnabled = true;
			} else {
				message             = jQuery( '#wt_ci_gdpr_only' ).val();
				ccpaSettingsEnabled = false;
			}
			jQuery( 'textarea[name="notify_message_field"]' ).val( message );
			jQuery( '.wt-cli-section-gdpr-ccpa .wt-cli-section-inner-' + consentType ).show();
			if ( ccpaSettingsEnabled === false ) {
				 jQuery( '.wt-cli-ccpa-element' ).hide();
				 jQuery( 'input[name=ccpa_enabled_field]' ).prop( "checked",false );
			} else {
				 jQuery( '.wt-cli-ccpa-element' ).show();
				 jQuery( 'input[name="ccpa_enabled_field"][value="' + ccpaEnabledState + '"]' ).prop( 'checked', true );
				if ( $event === true ) {
					jQuery( 'input[name="ccpa_enabled_field"]' ).prop( "checked",true );
				}

			}
			jQuery( '.wt-cli-toggle-content[data-cli-toggle-id="' + toggleTarget + '"]' ).show();
		}
		changeMessage();
		jQuery( 'input[type="radio"][name="consent_type_field"]' ).change(
			function() {
				changeMessage( true );
			}
		);

	}
);
