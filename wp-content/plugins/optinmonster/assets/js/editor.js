/* ==========================================================
 * editor.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */

import { getMonsterlink } from './Utils/monsterlink';

window.OMAPI_Editor = window.OMAPI_Editor || {};

/**
 * OptinMonster Classic Editor functionality.
 */
(function (window, document, $, app, undefined) {
	'use strict';

	// Make sure the OMAPI and OMAPI.monsterlink global is set.
	window.OMAPI = window.OMAPI || {};
	OMAPI.monsterlink = app.monsterlink;

	/**
	 * Get the currently active mce editor Id.
	 *
	 * @since 2.3.0
	 *
	 * @returns {string|undefined} Tinymce editor instance Id if found.
	 */
	app.getActiveEditorId = function () {
		let { wpActiveEditor, tinymce } = window;

		if (wp.media.editor.activeEditor) {
			wpActiveEditor = wp.media.editor.activeEditor;
		}

		if (!wpActiveEditor && tinymce && tinymce.activeEditor) {
			wpActiveEditor = tinymce.activeEditor.id;
		}

		return wpActiveEditor;
	};

	/**
	 * Get the active WP tinymce editor instance.
	 *
	 * @since 2.3.0
	 *
	 * @returns {Object|null} Tinymce editor instance or null if not found.
	 */
	app.getActiveEditor = function () {
		const editorId = app.getActiveEditorId();

		// No luck...
		if (!editorId || !window.tinymce) {
			return null;
		}

		return window.tinymce.get(editorId);
	};

	/**
	 * Insert the selected campaign monsterlkink to the editor.
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.mceLinkifyText = function () {
		const id = app.$select.val();
		if (id) {
			app.getActiveEditor().execCommand('mceInsertLink', false, {
				href: getMonsterlink(id),
				target: '_blank',
				rel: 'noopener noreferrer',
			});
		}
	};

	/**
	 * Open campaign monsterlink modal
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.modalOpenLink = function () {
		// Show our modal.
		app.$toToggle.addClass('optin-monster-modal-monsterlink').removeClass('optin-monster-modal-inline');

		app.$body.addClass('modal-open om-modal-open-monsterlink');
		app.$modalWrap.show();

		// When opening link modal, set "selected" option, if URL set.
		app.updateLinkSelectOptions(app.$select);

		// Trigger the original link link options button.
		// This is a hack...
		// We need this to be "open" (though we hide it with CSS)
		// In order for the mce selection to remain in place, otherwise focus shifts.
		const $optionsBtn = $('.wp-link-input').parent().find('.dashicons-admin-generic').parent();
		$optionsBtn.click();

		$(document).trigger('om-modal-open-monsterlink');
	};

	/**
	 * Open campaign shortcode modal
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.modalOpenInline = function () {
		app.$toToggle.addClass('optin-monster-modal-inline').removeClass('optin-monster-modal-monsterlink').show();

		app.$body.addClass('modal-open om-modal-open-inline');
		app.updateInlineSelectOptions();

		$(document).trigger('om-modal-open-inline');
	};

	/**
	 * Close campaign shortcode modal
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.modalClose = function () {
		// When closing our modals, empty value for our campaign selects.
		['$select', '$linkSelect', '$inlineSelect'].forEach((k) => {
			if (app[k] && app[k].length) {
				app[k].val('');
			}
		});

		app.$toToggle.hide();
		const type = app.$body.hasClass('om-modal-open-monsterlink') ? 'monsterlink' : 'inline';
		app.$body.removeClass('modal-open om-modal-open-monsterlink om-modal-open-inline');
		$(document).trigger(`om-modal-close-${type}`);
	};

	/**
	 * Insert the selected campaign shortcode to the editor.
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.insertShortcode = function () {
		const id = app.$inlineSelect.val();
		if (id) {
			wp.media.editor.insert(`[optin-monster slug="${id}" followrules="true"]`);
		}
	};

	/**
	 * If url already has value, check if it matches our monsterlink options.
	 *
	 * @since 2.3.0
	 *
	 * @param {Object} $select jQuery object for campaign-select element.
	 *
	 * @returns {void}
	 */
	app.updateLinkSelectOptions = function ($select) {
		const $selector = $('#wp-link-wrap #link-selector');
		const $search = $selector.find('#search-panel');
		const searchBottom = $search.offset().top + $search.outerHeight();
		const top = searchBottom - $selector.offset().top + 12; /* margin */

		$('.has-text-field #wp-link .query-results').css({ top });

		const url = $('.wp-link-input input.ui-autocomplete-input').val();
		if (url) {
			$select.find('option').each(function () {
				const val = $(this).val();
				if (val && url === getMonsterlink(val)) {
					$select.val(val);
				}
			});
		}
	};

	/**
	 * Disable any options already in use.
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.updateInlineSelectOptions = function () {
		const editorId = app.getActiveEditorId();

		// No luck...
		if (!editorId) {
			return;
		}

		const editor = app.getActiveEditor();
		const editorText = editor && !editor.isHidden() ? editor.getContent() : document.getElementById(editorId).value;

		// Set options to disabled if they are already used.
		app.$inlineSelect.find('option').each(function () {
			const $option = $(this);
			const hasShortcode = editorText.indexOf(`optin-monster slug="${$option.val()}"`) >= 0;
			$option.attr('disabled', hasShortcode);
		});
	};

	/**
	 * Add the monsterlink button to the wplink modal.
	 * (which triggers the monsterlink-select modal)
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.initLinkButton = function () {
		$('.wp-link-input').each(function () {
			const $modal = $(this).parent();

			if (!$modal.find('.optin-monster-insert-monsterlink').length) {
				const $div = $(
					'<div class="mce-widget mce-btn mce-last" tabindex="-1" role="button" aria-label="OptinMonster" style="margin-left:-3px;"></div>'
				);

				const $button = $(
					'<button role="presentation" type="button" tabindex="-1" class="optin-monster-insert-monsterlink"></button>'
				);
				$button.append($('.wp-media-buttons-icon.optin-monster-menu-icon').first().clone());

				$div.append($button);

				$modal.find('.mce-last').removeClass('mce-last');
				$modal.append($div);
			}
		});
	};

	/**
	 * Add the monsterlink select to the wplink advanced modal.
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.initAdvancedSettings = function () {
		const $advanced = $(`
			<p class="howto" id="om-link-campaign-label">${app.i18n.or_monsterlink}</p>
			<div style="margin-bottom: -8px;">
				${
					app.canMonsterlink
						? `<label><span>Select</span>
						<select name="om-link-class" id="om-link-campaign" aria-describedby="om-link-campaign-label">
						</select>
					</label>`
						: `<p class="om-monsterlink-upgrade"><span>${
								app.i18n.upgrade_monsterlink
						  }</span> <a href="${app.upgradeUri.replace(
								'--FEATURE--',
								'monster-link'
						  )}" target="_blank" rel="noopener">${app.i18n.upgrade}</a></p>`
				}
			</div>
		`);
		$advanced.find('select').html(app.$select.find('option').clone());
		if ($advanced.find('.om-monsterlink-upgrade').length) {
			const $clone = $('#om-monsterlink-upgrade').clone();
			$advanced.find('.om-monsterlink-upgrade span').html($clone.html());
		}

		$('#link-options').append($advanced);
		app.$linkSelect = $('#om-link-campaign');

		// Monkey-patch the wpLink.getAttrs method to handle monster-link target/rel attributes.
		if (typeof window.wpLink !== 'undefined') {
			const orig = wpLink.getAttrs;
			wpLink.getAttrs = function () {
				const attrs = orig();
				const ml = getMonsterlink(app.$linkSelect.val());

				if (attrs.href === ml) {
					attrs.target = '_blank';
					attrs.rel = 'noopener noreferrer';
				}

				return attrs;
			};
		}
	};

	/**
	 * Handles modifying the wplink modals to inject monsterlink options.
	 *
	 * @since 2.3.0
	 *
	 * @param {Object} editor The editor object.
	 *
	 * @returns {void}
	 */
	app.initEditorMods = function (editor) {
		if (!editor || editor.hasInitiatedOm) {
			return;
		}

		editor.hasInitiatedOm = true;

		editor.on('ExecCommand', function (e) {
			if ('WP_Link' === e.command) {
				app.initLinkButton();
			}
		});

		if (!app.$linkSelect) {
			app.initAdvancedSettings();
		}
	};

	/**
	 * Setup our event listeners.
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.setupListeners = function () {
		$(document)
			// Open inline modal when media button is clicked
			.on('click', '.optin-monster-insert-campaign-button', function (event) {
				event.preventDefault();
				app.modalOpenInline();
			})

			// Open link modal when monsterlink button is clicked
			.on('click', '.optin-monster-insert-monsterlink', function (event) {
				event.preventDefault();
				app.modalOpenLink();
			})

			// Close modal on close or cancel links or background click.
			.on(
				'click',
				'#optin-monster-modal-backdrop, #optin-monster-modal-close, #optin-monster-modal-cancel a',
				function (event) {
					event.preventDefault();
					app.modalClose();
				}
			)

			// When submitting the inline campaign selection,
			// Insert the shortcode, and close the modal.
			.on('click', '#optin-monster-modal-submit-inline', function (event) {
				event.preventDefault();
				app.insertShortcode();
				app.modalClose();
			})

			// When submitting the link modal selection,
			// Insert the link, and close the modal.
			.on('click', '#optin-monster-modal-submit', function (event) {
				event.preventDefault();
				app.mceLinkifyText();
				app.modalClose();
			})

			// When changing our campaigns select in the wplink modal,
			// update the link url/target values as well.
			.on('change', '#om-link-campaign', function () {
				const id = app.$linkSelect.val();
				if (id) {
					$('#wp-link-url').val(getMonsterlink(id));
					$('#wp-link-target').prop('checked', true);
				}
			})

			// When opening wplink modal, set "selected" option.
			.on('wplink-open', function (wrap) {
				app.updateLinkSelectOptions(app.$linkSelect);
			})

			// When closing wplink modal, close our modals too.
			.on('wplink-close', function (wrap) {
				app.modalClose();
			})

			// When closing our link modal, also close the wplink modal
			.on('om-modal-close-monsterlink', function (wrap) {
				if (wpLink) {
					// If in tinymce mode, close the (hidden) wplink modal as well.
					const editor = app.getActiveEditor();
					if (editor && !editor.isHidden()) {
						wpLink.close();
					}
				}
			});
	};

	/**
	 * Kicks things off when the DOM is ready.
	 *
	 * @since 2.3.0
	 *
	 * @returns {void}
	 */
	app.init = function () {
		// Store cached nodes.
		app.$body = $(document.body);
		app.$modalWrap = $('#optin-monster-modal-wrap');
		app.$toToggle = $('#optin-monster-modal-backdrop, #optin-monster-modal-wrap');
		app.$select = $('#optin-monster-modal-select-campaign');
		app.$inlineSelect = $('#optin-monster-modal-select-inline-campaign');
		app.$linkSelect = null;

		app.setupListeners();

		// Init the editor mods if we have an active editor.
		app.initEditorMods(app.getActiveEditor());

		if (typeof tinymce !== 'undefined') {
			// Also init the editor mods whenever a new editor
			// is initiated (looking at you, Elementor).
			tinymce.on('SetupEditor', function ({ editor }) {
				app.initEditorMods(editor);
			});
		}
	};

	$(app.init);
})(window, document, jQuery, window.OMAPI_Editor);
