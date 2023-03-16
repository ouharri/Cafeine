/* ==========================================================
 * metabox.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
window.OMAPI_WooCommerce_Metaboxes = window.OMAPI_WooCommerce_Metaboxes || {};

/**
 * Metabox Tabs
 *
 * This contains the functionality for our WooCommerce education metaboxes.
 *
 * @since 2.1.0
 */
(function (window, document, app, undefined) {
	/**
	 * Cache DOM objects.
	 *
	 * Setup everything needed.
	 *
	 * @since 2.1.0
	 *
	 * @returns {void}
	 */
	app.cache = () => {
		app.options = document.querySelectorAll('.omapi-metabox__nav a');
		app.slides = document.querySelectorAll('.omapi-metabox__slides-slide');
	};

	/**
	 * Set Event Listeners
	 *
	 * Loop through each navigation option and set the event listener.
	 *
	 * @since 2.1.0
	 *
	 * @returns {void}
	 */
	app.setEventListeners = () => {
		app.options.forEach((option) => {
			option.addEventListener('click', (e) => {
				e.preventDefault();

				app.removeActiveClass(app.options);
				option.classList.add('active');

				const target = option.getAttribute('href');
				if (target) {
					app.removeActiveClass(app.slides);

					document.querySelector(target).classList.add('active');
				}
			});
		});
	};

	/**
	 * Remove Active Class
	 *
	 * @param {NodeList} options the tab options.
	 *
	 * @since 2.1.0
	 *
	 * @returns {void}
	 */
	app.removeActiveClass = (options) => {
		options.forEach((option) => {
			option.classList.remove('active');
		});
	};

	// Set the event listeners once the DOM is ready.
	window.addEventListener('DOMContentLoaded', () => {
		app.hasSlides = document.querySelectorAll('.omapi-metabox.has-slides').length;

		if (app.hasSlides) {
			app.cache();
			app.setEventListeners();
		}
	});
})(window, document, window.OMAPI_WooCommerce_Metaboxes);
