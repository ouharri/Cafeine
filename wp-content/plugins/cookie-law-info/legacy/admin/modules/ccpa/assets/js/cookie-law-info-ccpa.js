(function( $ ) {
	'use strict';
	var CCPA = {
		ccpaOptedOut: false,
		ccpaOptOutConfirmationOpen: false,
		set: function() {
			this.setCheckboxState();
			jQuery( document ).on(
				'click',
				'.wt-cli-ccpa-opt-out-checkbox',
				function() {
					CCPA.ccpaOptedOut = false;
					if (this.checked === true ) {
						CCPA.ccpaOptedOut = true;
					}
					CCPA.optOutCcpa();
				}
			);
			jQuery( document ).on(
				'click',
				'.wt-cli-ccpa-opt-out:not(.wt-cli-ccpa-opt-out-checkbox)',
				function(){
					CCPA.showCcpaOptOutConfirmBox();
				}
			)
		},
		setCheckboxState : function() {
			var cliConsent       = {};
			var preferenceCookie = CLI_Cookie.read( CLI_PREFERNCE_COOKIE );
			if ( preferenceCookie !== null ) {
				cliConsent = CCPA.parseCookie( preferenceCookie );
				if ( typeof( cliConsent.ccpaOptout ) !== 'undefined') {

					if ( cliConsent.ccpaOptout === true ) {
						jQuery( '.wt-cli-ccpa-opt-out-checkbox' ).prop( 'checked',true );
					} else {
						jQuery( '.wt-cli-ccpa-opt-out-checkbox' ).prop( 'checked',false );
					}
				}

			}
		},
		optOutCcpa: function() {
			var preferenceCookie = CLI_Cookie.read( CLI_PREFERNCE_COOKIE );
			var cliConsent       = {};
			if ( preferenceCookie !== null ) {
				cliConsent = CCPA.parseCookie( preferenceCookie );
			}
			cliConsent.ccpaOptout = this.ccpaOptedOut;
			cliConsent            = JSON.stringify( cliConsent );
			cliConsent            = window.btoa( cliConsent );
			CLI_Cookie.set( CLI_PREFERNCE_COOKIE,cliConsent,CLI_ACCEPT_COOKIE_EXPIRE );
			this.setCheckboxState();
		},
		parseCookie: function( preferenceCookie ) {
			var cliConsent = {};
			cliConsent     = window.atob( preferenceCookie );
			cliConsent     = JSON.parse( cliConsent );
			return cliConsent;
		},
		toggleCCPA: function() {

		},
		checkAuthentication: function() {

		},
		showCcpaOptOutConfirmBox: function() {

			var css                         = '.cli-alert-dialog-buttons button {\
                -webkit-box-flex: 1!important;\
                -ms-flex: 1!important;\
                flex: 1!important;\
                -webkit-appearance: none!important;\
                -moz-appearance: none!important;\
                appearance: none!important;\
                margin: 4px!important;\
                padding: 8px 16px!important;\
                border-radius: 64px!important;\
                cursor: pointer!important;\
                font-weight: 700!important;\
                font-size: 12px !important;\
                color: #fff;\
                text-align: center!important;\
                text-transform: capitalize;\
                border: 2px solid #61a229;\
            } #cLiCcpaOptoutPrompt .cli-modal-dialog{\
                max-width: 320px;\
            }\
            #cLiCcpaOptoutPrompt .cli-modal-content {\
                box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);\
            -webkit-box-shadow:0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);\
            -moz-box-shadow0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);\
            }\
            .cli-alert-dialog-content {\
                font-size: 14px;\
            }\
            .cli-alert-dialog-buttons {\
                padding-top:5px;\
            }\
            button.cli-ccpa-button-cancel {\
                background: transparent !important;\
                color: #61a229;\
            }\
            button.cli-ccpa-button-confirm {\
                background-color:#61a229;\
                color:#ffffff;\
            }';
			var head                        = document.head || document.getElementsByTagName( 'head' )[0];
			var style                       = document.createElement( 'style' );
			var primaryColor                = CLI.settings.button_1_button_colour;
			var primaryLinkColor            = CLI.settings.button_1_link_colour;
			var backgroundColor             = CLI.settings.background;
			var textColor                   = CLI.settings.text;
			CCPA.ccpaOptOutConfirmationOpen = false;
			var ccpaPrompt,
			$this                           = this;
			(ccpaPrompt = document.createElement( "div" )).classList.add( "cli-modal", "cli-show", "cli-blowup" );
			ccpaPrompt.id = "cLiCcpaOptoutPrompt";
			var t         = document.createElement( "div" );
			t.className   = "cli-modal-dialog";
			var n         = document.createElement( "div" );
			n.classList.add( "cli-modal-content","cli-bar-popup" );
			var o       = document.createElement( "div" );
			o.className = "cli-modal-body";
			var p       = document.createElement( "div" );
			p.classList.add( "wt-cli-element", "cli-container-fluid", "cli-tab-container" );
			var q       = document.createElement( "div" );
			q.className = "cli-row";
			var r       = document.createElement( "div" );
			r.classList.add( "cli-col-12" );
			var x = document.createElement( "div" );
			x.classList.add( "cli-modal-backdrop", "cli-fade", "cli-settings-overlay", "cli-show" );
			var a       = document.createElement( "button" );
			var b       = document.createElement( "button" );
			var c       = document.createElement( "div" );
			var d       = document.createElement( "div" );
			d.className = "cli-alert-dialog-content",
			d.innerText = ccpa_data.opt_out_prompt,
			c.className = "cli-alert-dialog-buttons";
			a.className = "cli-ccpa-button-confirm",
			a.innerText = ccpa_data.opt_out_confirm,
			a.addEventListener(
				"click",
				function() {
					CCPA.ccpaOptedOut = true,
					CCPA.optOutCcpa(),
					document.body.removeChild( ccpaPrompt ),
					document.body.removeChild( x ),
					document.body.classList.remove( "cli-modal-open" );
					head.removeChild( style );
					if ( Cli_Data.ccpaType === 'ccpa' ) {
						CLI.enableAllCookies();
						CLI.accept_close();
					}
				}
			),
			b.className = "cli-ccpa-button-cancel",
			b.innerText = ccpa_data.opt_out_cancel,
			b.addEventListener(
				"click",
				function() {
					CCPA.ccpaOptedOut = false,
					CCPA.optOutCcpa(),
					document.body.removeChild( ccpaPrompt ),
					document.body.removeChild( x ),
					document.body.classList.remove( "cli-modal-open" );
					head.removeChild( style );
					if ( Cli_Data.ccpaType === 'ccpa' ) {
						CLI.enableAllCookies();
						CLI.accept_close();
					}
				}
			),
			ccpaPrompt.addEventListener(
				"click",
				function( event ) {
					event.stopPropagation();
					if ( ccpaPrompt !== event.target) {
						return;
					}
					document.body.removeChild( ccpaPrompt ),
					document.body.removeChild( x ),
					document.body.classList.remove( "cli-modal-open" );
					head.removeChild( style );

				}
			),
			ccpaPrompt.appendChild( t ),
			t.appendChild( n ),
			n.appendChild( o ),
			o.appendChild( p ),
			p.appendChild( q ),
			q.appendChild( r ),
			r.appendChild( d ),
			r.appendChild( c ),
			c.appendChild( b ),
			c.appendChild( a ),

			head.appendChild( style );
			style.type = 'text/css';
			if (style.styleSheet) {
				// This is required for IE8 and below.
				style.styleSheet.cssText = css;
			} else {
				style.appendChild( document.createTextNode( css ) );
			}
			document.body.appendChild( ccpaPrompt );
			document.body.appendChild( x );
			document.body.classList.add( "cli-modal-open" );

		},
	}
	jQuery( document ).ready(
		function() {
			CCPA.set();
		}
	);

})( jQuery );
