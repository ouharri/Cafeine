/* ==========================================================
 * mailpoet.js
 * ==========================================================
 * Copyright 2022 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
jQuery(document).ready(function ($) {
	// Legacy Campaigns
	$(document).on('OptinMonsterPreOptin', function (event, optin, object) {
		var slug = optin.optin.replace('-', '_');
		$.each(omapi_localized.slugs, function (i, v) {
			if (!v.mailpoet) {
				return;
			}

			if (i !== slug) {
				return;
			}

			// Send a request to force optin to work even if no provider is set.
			var data = optin.optin_data;
			object.setProp('optin_data', data);

			data.optin = optin.original_optin;

			// Post to MailPoet.
			postToMailPoet(data);

			return false;
		});
	});

	// Default Campaigns
	document.addEventListener('om.Optin.init.submit', function (event) {
		var campaign = event.detail.Campaign;
		var optin = event.detail.Optin;

		$.each(omapi_localized.slugs, function (i, v) {
			if (!v.mailpoet) {
				return;
			}

			if (i !== campaign.id) {
				return;
			}

			// Send a request to force optin to work even if no provider is set.
			var data = optin.data;
			data.optin = campaign.id;

			if (data.fields) {
				$.extend(data, data.fields);
			}

			// Post to MailPoet.
			postToMailPoet(data);

			return false;
		});
	});

	function postToMailPoet(data) {
		// Now make an ajax request to make the optin locally.
		$.post(
			omapi_localized.ajax,
			{
				action: 'mailpoet',
				nonce: omapi_localized.nonce,
				no_provider: true,
				optinData: data,
			},
			function () {},
			'json'
		);
	}
});
