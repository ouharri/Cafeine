/* ==========================================================
 * plugininstall.js
 * ==========================================================
 * Copyright 2022 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
window.OMAPI_Plugins = window.OMAPI_Plugins || {};
(function (window, document, $, app, undefined) {
	'use strict';

	app.handleSubmission = (event) => {
		event.preventDefault();
		if (!app.pluginData.status) {
			throw new Error('Missing Plugin Data');
		}

		const $install = $('.button-install');
		const $activate = $('.button-activate');
		const installText = $install.html();
		const activateText = $activate.html();

		$install.html($install.data('actiontext'));
		$activate.html($activate.data('actiontext'));

		$('#om-plugin-alerts').hide();

		$.ajax({
			type: 'POST',
			beforeSend: function (request) {
				request.setRequestHeader('X-WP-Nonce', app.restNonce);
			},
			url: app.restUrl + 'omapp/v1/plugins/',
			data: {
				id: app.pluginData.id,
				actionNonce: app.actionNonce,
			},
			success: function (data) {
				window.location.reload();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$install.html(installText);
				$activate.html(activateText);

				let message = 'Something went wrong!';
				if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
					message += '<br>Error found: ' + jqXHR.responseJSON.message;
				}
				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					try {
						message += `<br>(data: ${JSON.stringify(jqXHR.responseJSON.data)})`;
					} catch (e) {}
				}

				const action = app.pluginData.installed ? 'activate' : 'install';
				// eslint-disable-next-line no-console
				console.error(`Could not ${action} the ${app.pluginData.name} plugin`, {
					jqXHR,
					textStatus,
					errorThrown,
				});

				$('#om-plugin-alerts').show().html($('<p/>').html(message));
			},
		});
	};

	app.init = function () {
		$('body').on('submit', '.install-plugin-form', app.handleSubmission);
	};

	$(app.init);
})(window, document, jQuery, window.OMAPI_Plugins);
