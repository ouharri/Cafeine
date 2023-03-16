/* ==========================================================
 * admin.js
 * ==========================================================
 * Copyright 2020 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */
jQuery(document).ready(function ($) {
	/**
	 * Updates links which correspond to Vue's router to use the Vue router instead,
	 * if it's available.
	 *
	 * @since  2.0.0
	 *
	 * @returns {void}
	 */
	function omapiHandleAppRouterLinks() {
		const links = document.querySelectorAll('a[href*="?page=optin-monster-"]');
		links.forEach((a) => {
			a.addEventListener('click', (evt) => {
				if (window.omWpApi && window.omWpApi.main) {
					const router = window.omWpApi.main.app.$router;
					const url = evt.target.search || evt.target.closest('a').search;
					const route = router.getRouteForQuery(url);

					if (route) {
						if (window.omWpApi.elRemove) {
							$(window.omWpApi.elRemove).remove();
						}

						evt.preventDefault();
						router.push({ name: route.name });
					}
				}
			});
		});
	}

	omapiHandleAppRouterLinks();
});
