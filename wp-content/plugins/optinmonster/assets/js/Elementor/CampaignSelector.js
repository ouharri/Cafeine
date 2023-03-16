'use strict';

import { getOptions, getCampaign } from '../Utils/campaigns';

const removed = [];
OMAPI._usedSlugs = OMAPI._usedSlugs || {};

class CampaignSelector extends elementorModules.frontend.handlers.Base {
	static $editorSelect = null;
	static instances = [];

	getDefaultSettings() {
		return {
			selectors: {
				holder: '.om-elementor-editor .om-elementor-holder',
				select: '.om-elementor-editor select',
				links: '.om-elementor-editor a',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');

		return {
			$holder: this.$element.find(selectors.holder),
			$select: this.$element.find(selectors.select),
			$links: this.$element.find(selectors.links),
		};
	}

	bindEvents() {
		CampaignSelector.instances.push(this);

		this.oldSlug = this.campaignSlug();
		this.campaignLoaded = false;

		this.on('campaignLoaded', () => {
			this.campaignLoaded = true;
			this.$element.find('.om-elementor-editor .om-errors').hide();
		});

		this.on('otherCampaignLoaded', this.updateSelect.bind(this));
		this.on('otherCampaignRemoved', this.updateSelect.bind(this));
		this.on('campaignError', this.foundError.bind(this));

		this.elements.$select.on('change', this.onSelect.bind(this));
		this.elements.$links.on('click', this.onClickLinks.bind(this));

		this.initCampaignPreview();
		this.maybeTogglePanelSettings();
	}

	unbindEvents() {
		const campaign = this.getCampaign();

		if (campaign) {
			removed.push(campaign);
			campaign.off();
		}
		if (this.oldSlug) {
			delete OMAPI._usedSlugs[this.oldSlug];

			window.OMAPI_Elementor.utils.events.trigger(document, 'Plugin.Elementor.Instance.removed', {
				id: this.oldSlug,
			});
		}
	}

	/**
	 * Handles get-campaigns error.
	 *
	 * @2.2.0
	 *
	 * @param  {Object} error Error event object
	 *
	 * @returns {void}
	 */
	foundError(error) {
		const slug = this.campaignSlug();

		if (error.responseURL && 0 > error.responseURL.indexOf(slug)) {
			return;
		}

		let msg = error;

		if (error.response) {
			msg = JSON.parse(error.response).message || JSON.parse(error.response).error;
		}

		if (error.message) {
			msg = error.message;
		}

		this.$element.find('.om-elementor-editor .om-errors').show().find('.om-error-description').html(msg);
	}

	onClickLinks(event) {
		event.preventDefault();
		window.open(event.target.href);
	}

	onSelect(event) {
		event.preventDefault();
		const $select = window.parent.jQuery(
			'#elementor-controls .elementor-control-campaign_id select[data-setting="campaign_id"]'
		);

		$select.val(this.elements.$select.val()).trigger('change');
	}

	onElementChange(propertyName, controlView, elementView) {
		const $editorSelect = controlView.$el.find('select[data-setting="campaign_id"]');
		if ($editorSelect.length) {
			CampaignSelector.$editorSelect = $editorSelect;
		}

		this.maybeTogglePanelSettings();

		if ('campaign_id' === propertyName) {
			this.initCampaignPreview();
		}
	}

	initCampaignPreview() {
		this.initCampaign();
		this.updateSelect();
	}

	initCampaign() {
		const slug = this.campaignSlug();
		if (!slug) {
			return;
		}

		if (this.oldSlug) {
			delete OMAPI._usedSlugs[this.oldSlug];
		}
		this.oldSlug = slug;

		OMAPI._usedSlugs[slug] = true;

		this.elements.$holder.html(`<div id="om-${slug}-holder"></div>`);

		let campaign = this.getCampaign();
		if (campaign) {
			return;
		}

		if (removed.length) {
			campaign = removed.find((c) => slug === c.id);
			if (campaign) {
				removed.splice(removed.indexOf(campaign), 1);
				return setTimeout(() => campaign.reset(), 200);
			}
		}

		if (campaign) {
			return;
		}

		const embed = {
			id: `om-${slug}-js`,
			type: 'text/javascript',
			src: OMAPI.apiUrl,
			async: true,
			'data-user': OMAPI.omUserId,
			'data-campaign': slug,
		};
		if (OMAPI.omEnv) {
			embed['data-env'] = OMAPI.omEnv;
		}

		// Attempt to append it to the <head>, otherwise append to the document.
		const head = document.getElementsByTagName('head')[0] || document.documentElement;
		const newScript = document.createElement('script');
		let att;
		for (att in embed) {
			newScript.setAttribute(att, embed[att]);
		}

		head.appendChild(newScript);
	}

	updateSelect() {
		this.updateSelectOptions(this.elements.$select);
	}

	updateSelectOptions($select) {
		const slug = this.campaignSlug();
		const fragment = document.createDocumentFragment();
		$select.find('option').remove();
		getOptions('inline', slug).forEach((o) => {
			const option = document.createElement('option');
			option.textContent = o.label;
			option.value = o.value;
			if (o.selected) {
				option.selected = true;
			}

			if (o.disabled) {
				option.disabled = true;
			}

			fragment.appendChild(option);
		});
		$select.append(fragment);
	}

	/**
	 * Get the campaign slug from element settings.
	 *
	 * @since  2.2.0
	 *
	 * @returns {string} Campaign slug.
	 */
	campaignSlug() {
		return this.getElementSettings('campaign_id');
	}

	/**
	 * Get the global campaign object from OM API.
	 *
	 * @since  2.2.0
	 *
	 * @returns {Object|null} The global campaign object or null.
	 */
	getCampaign() {
		return getCampaign(this.campaignSlug());
	}

	/**
	 * Called from parent classs.
	 *
	 * @since  2.2.0
	 *
	 * @param  {string} changed The thing that changed.
	 *
	 * @returns {void}
	 */
	onEditSettingsChange(changed) {
		if ('panel' === changed) {
			this.maybeTogglePanelSettings();
		}
	}

	maybeTogglePanelSettings() {
		setTimeout(() => {
			const page = elementor.getPanelView().getCurrentPageView();
			const slug = this.campaignSlug();

			if (page.getControlViewByName) {
				['edit_campaign', 'followrules', 'campaign_id'].forEach((k) => {
					const model = page.getControlModel(k);

					if (!model) {
						return;
					}

					const view = page.getControlViewByModel(model);
					if (!view) {
						return;
					}

					if ('campaign_id' === k) {
						const $select = view.$el.find('[data-setting="campaign_id"]');
						if ($select.length) {
							this.updateSelectOptions($select);
						}
					} else {
						// Toggle these controls from view.
						view.$el[slug ? 'show' : 'hide']();
					}

					if (slug && 'edit_campaign' === k) {
						const $link = view.$el.find('a');
						if ($link.length) {
							$link.attr('href', OMAPI.editUrl.replace(/--CAMPAIGN_SLUG--/g, slug));
						}
					}
				});
			}
		}, 10);
	}
}

export default CampaignSelector;
