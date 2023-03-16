/* ==========================================================
 * wc-marketing.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
window.OMAPI_WooCommerce_Marketing = window.OMAPI_WooCommerce_Marketing || {};

(function (window, document, $, app, undefined) {
	'use strict';

	app.interval;

	/**
	 * Add Education Box
	 *
	 * @since 2.1.0
	 *
	 * @returns {void}
	 */
	app.insertEducationBox = function () {
		// When the Marketing Hub was introduced in 4.1, the class
		// name used for their cards was ".woocommerce-card". Here
		// we'll check for that first and use it if found. Otherwise,
		// we'll use the current class name.
		const $earlyCard = $('.woocommerce-card:nth-child(2)');
		const $card = $earlyCard.length ? $earlyCard : $('.components-card:nth-child(2)');
		const $newCard = $(document.getElementById('components-card-om'));

		if ($card.length) {
			$card.after($newCard.show());
		}
	};

	app.initBox = function () {
		if ($('.woocommerce-marketing-overview').length) {
			if (app.interval) {
				clearInterval(app.interval);
			}
			app.insertEducationBox();
		}
	};

	app.init = function () {
		// We have to wait for the Woo React app to finish before
		// we can insert our box, So we'll keep trying until we get
		// what we're looking for.
		app.interval = setInterval(() => app.initBox(), 1000);
		app.initBox();
	};

	$(app.init);
})(window, document, jQuery, window.OMAPI_WooCommerce_Marketing);
