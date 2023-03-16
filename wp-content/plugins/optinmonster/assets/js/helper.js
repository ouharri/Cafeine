/* ==========================================================
 * helper.js
 * ==========================================================
 * Copyright 2022 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
window.OMAPI_Helper = window.OMAPI_Helper || {};
(function (window, document, app) {
	'use strict';

	app.fixIds = [];

	/**
	 * Add campaign id to "fixed" ids and maybe append the style fix.
	 *
	 * @param {Object} form        The WPForm object.
	 * @param {Integer} campaignId The campaign id/slug.
	 *
	 * @returns {boolean} True if date/time picker fields exist.
	 */
	app.maybeFixZindex = (form, campaignId) => {
		// If the campaign has already been "fixed," bail.
		if (-1 !== app.fixIds.indexOf(campaignId) || document.getElementById('om-wpforms-zindex')) {
			return;
		}

		// If picker fields exist in the form, add it.
		const pickers = form.querySelectorAll('.wpforms-datepicker, .wpforms-timepicker');
		if (pickers.length) {
			app.fixIds.push(campaignId);
		}

		// Append style element with the z-index fix to the head.
		const style = document.createElement('style');
		style.id = 'om-wpforms-zindex';
		style.innerText = '.flatpickr-calendar.open, .ui-timepicker-wrapper { z-index: 999999999 !important; }';

		document.head.appendChild(style);
	};

	/**
	 * Remove the campaign id/style element.
	 *
	 * @param {Integer} campaignId The campaign id/slug.
	 *
	 * @returns {void}
	 */
	app.maybeRemoveCssFix = (campaignId) => {
		// Remove the campaign id.
		const index = app.fixIds.indexOf(campaignId);
		if (index > -1) {
			app.fixIds.splice(index, 1);
		}

		// If there are no more ids to "fix," remove the styles.
		if (!app.fixIds.length) {
			document.getElementById('om-wpforms-zindex').remove();
		}
	};

	document.addEventListener('om.Styles.positionFloating', function (event) {
		var campaign = event.detail.Campaign;
		if (
			'floating' === campaign.Types.type &&
			'top' === campaign.options.position &&
			document.getElementById('wpadminbar')
		) {
			const marginTop = window.matchMedia('(max-width: 782px)').matches ? '46px' : '32px';
			campaign.contain.style.marginTop = marginTop;
		}
	});

	const wpfEventCallback = (event, cb) => {
		const campaignId = event.detail.Campaign.id;
		const forms = document.querySelectorAll(`#om-${campaignId} form`);

		window._omapp._utils.helpers.each(forms, (i, form) => {
			const isWPForms = form.id ? -1 !== form.id.indexOf('wpforms-form-') : false;

			if (isWPForms) {
				cb(campaignId, form);
			}
		});
	};

	// Find any WPForms forms and listen for a submission to trigger a conversion.
	document.addEventListener('om.Html.append.after', (event) => {
		wpfEventCallback(event, (campaignId, form) => {
			const cb = () => {
				// Ensure WPForms has time to add errors to the DOM.
				setTimeout(() => {
					const hasError = document.querySelectorAll('.wpforms-has-error, .wpforms-error');

					if (!hasError.length) {
						window._omapp._utils.events.trigger(form, 'omWpformsSuccess');
					}
				}, 500);
			};

			window._omapp._utils.helpers.on(form, 'submit.omWpformsConversion', cb);
			app.maybeFixZindex(form, campaignId);
		});
	});

	// Remove WPForms listener on campaign close.
	document.addEventListener('om.Campaign.startClose', (event) => {
		wpfEventCallback(event, (campaignId, form) => {
			window._omapp._utils.helpers.off(form, 'submit.omWpformsConversion');
			app.maybeRemoveCssFix(campaignId);
		});
	});
})(window, document, window.OMAPI_Helper);
