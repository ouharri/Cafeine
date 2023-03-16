/* ==========================================================
 * elementor.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */

'use strict';

import CampaignSelector from './Elementor/CampaignSelector';

window.OMAPI_Elementor = window.OMAPI_Elementor || {};

(function (window, document, $, app, undefined) {
	/**
	 * Tells the campaign being initiated that it is in preview mode (form will not work).
	 *
	 * @since  2.2.0
	 *
	 * @param  {Object} evt Event
	 *
	 * @returns {void}
	 */
	app.setAsPreview = (evt) => {
		// Disable form fields if called from the Gutenberg editor.
		evt.detail.Campaign.preview = true;
	};

	/**
	 * Triggers a load event for backbone models to listen to.
	 *
	 * @since  2.2.0
	 *
	 * @param  {Object} evt Event
	 *
	 * @returns {void}
	 */
	app.triggerLoaded = (evt) => {
		const { id } = evt.detail.Campaign;

		CampaignSelector.instances.forEach((instance) => {
			instance.trigger(instance.campaignSlug() === id ? 'campaignLoaded' : 'otherCampaignLoaded');
		});
	};

	/**
	 * Handles outputting api.js render errors in the block when necessary.
	 *
	 * @since  2.2.0
	 *
	 * @param  {[type]} evt [description]
	 *
	 * @returns {[type]}     [description]
	 */
	app.triggerError = (evt) => {
		let { Campaign, Main, error } = evt.detail;

		const id = Main ? Main.defaults.campaign : Campaign ? Campaign.id : '';
		if (!id) {
			return;
		}

		const instance = CampaignSelector.instances.find((i) => i.campaignSlug() === id);
		if (!instance) {
			return;
		}

		if (error.response) {
			error = JSON.parse(error.response).message || JSON.parse(error.response).error;
		}

		instance.trigger('campaignError', error);
	};

	/**
	 * Triggers events for backbone models whenever a campaign is removed.
	 *
	 * @since  2.2.0
	 *
	 * @param  {Object} evt Event
	 *
	 * @returns {void}
	 */
	app.triggerRemove = (evt) => {
		const { id } = evt.detail;

		CampaignSelector.instances.forEach((instance) => {
			if (instance.campaignSlug() !== id) {
				instance.trigger('otherCampaignRemoved', id);
			}
		});
	};

	app.init = function () {
		// Disable form fields
		document.addEventListener('om.Campaign.init', app.setAsPreview);

		// Store the API utils.
		document.addEventListener('om.Main.init', ({ detail }) => (app.utils = detail._utils));

		// Disable all non-inline campaigns from Elementor preview.
		document.addEventListener('om.WebFonts.init', function (evt) {
			var campaign = evt.detail.Campaign;
			if (!campaign.Types.isInline()) {
				campaign.off();
			}
		});

		$(window).on('elementor/frontend/init', function () {
			// Widget buttons click.
			elementor.channels.editor.on('elementorOMAPIAddInlineBtnClick', () =>
				window.open(OMAPI.templatesUri + '&type=inline')
			);
			elementor.channels.editor.on('elementorOMAPICreateAccount', () => window.open(OMAPI.wizardUri));
			elementor.channels.editor.on('elementorOMAPIConnectAccount', () => window.open(OMAPI.settingsUri));

			elementorFrontend.hooks.addAction('frontend/element_ready/optinmonster.default', ($element) => {
				elementorFrontend.elementsHandler.addHandler(CampaignSelector, { $element });
			});

			document.addEventListener('om.Campaign.afterShow', app.triggerLoaded);
			document.addEventListener('om.Main.getCampaigns.error', app.triggerError);
			document.addEventListener('om.Campaign.show.error', app.triggerError);
			document.addEventListener('om.Campaign.load.error', app.triggerError);
			document.addEventListener('om.Plugin.Elementor.Instance.removed', app.triggerRemove);
		});
	};

	app.init();
})(window, document, jQuery, window.OMAPI_Elementor);
