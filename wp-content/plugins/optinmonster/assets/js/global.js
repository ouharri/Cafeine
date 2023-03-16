/* ==========================================================
 * global.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
window.OMAPI_Global = window.OMAPI_Global || {};
(function (window, document, $, app, undefined) {
	'use strict';

	app.updateNotifications = function () {
		$.ajax({
			async: true,
			url: app.url,
			headers: {
				'x-wp-nonce': app.nonce,
			},
		}).done(function (response) {
			// If the app is running, we don't need to proceed (the app handles it).
			if (window.omWpApi) {
				return;
			}

			var total = response.length;
			var $name = app.$.menu.find('.toplevel_page_optin-monster-dashboard .wp-menu-name');
			var $count = $name.find('.om-notifications-count');
			var countString = String(total);
			var classes = 'om-notifications-count update-plugins count-' + countString;

			if ($count.length) {
				$count.attr('class', classes);
				$count.find('.plugin-count').text(countString);
			} else {
				$name.html(
					'OptinMonster <span class="' +
						classes +
						'"><span class="plugin-count">' +
						countString +
						'</span></span>'
				);
			}
		});
	};

	app.init = function () {
		app.$ = {
			menu: $(document.getElementById('toplevel_page_optin-monster-dashboard')),
		};

		if (app.upgradeUrl) {
			app.$.menu
				.find('.wp-submenu [href="admin.php?page=optin-monster-upgrade"]')
				.attr('target', '_blank')
				.attr('rel', 'noopener')
				.attr('href', app.upgradeUrl);

			app.$.menu.find('.om-menu-highlight').closest('li').addClass('om-submenu-highlight');
		}

		// If the app is not running, and we should fetch updated notifications...
		if (!window.omWpApi && app.fetchNotifications) {
			app.updateNotifications();
		}
	};

	$(app.init);
})(window, document, jQuery, window.OMAPI_Global);
