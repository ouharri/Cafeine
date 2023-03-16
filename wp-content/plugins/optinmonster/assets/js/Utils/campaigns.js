import get from 'lodash/get';
import ArchieIcon from '../Components/Icons/Archie';
import CampaignSelector from '../Components/Blocks/CampaignSelector';
import { hasSites } from './sites';
const { __ } = wp.i18n;

/**
 * Get available inline campaign select options.
 *
 * @since  [since]
 *
 * @param  {string}  type       The campaign generic type (inline or other).
 * @param  {string}  slug       The campaign slug.
 * @param  {boolean} checkSites Whether to check for sites (return empty result if no sites connected).
 *
 * @returns {Array}              Array of campaign options for select elements.
 *                              Includes value, label, and selected/disabled properties.
 */
export const getOptions = (type, slug = null, checkSites = true) => {
	if (checkSites && !hasSites()) {
		return [];
	}

	const campaigns = get(OMAPI, `campaigns.${type}`, {});
	if (Object.keys(campaigns).length < 1 || !OMAPI.omUserId) {
		return [];
	}

	let available = Object.keys(campaigns).map((value) => {
		let label = get(campaigns, `${value}.title`, '');
		if (get(campaigns, `${value}.pending`)) {
			label += ' [Pending]';
		}

		const selected = null !== slug && slug === value;
		const disabled = null !== slug && get(OMAPI, `_usedSlugs.${value}`) && value !== slug;

		return { value, label, selected, disabled };
	});

	if (available.length > 0) {
		available.unshift({ value: '', label: OMAPI.i18n.campaign_select });
	}

	return available;
};

/**
 * Get the global campaign object from OM API for given slug.
 *
 * @since 2.3.0
 *
 * @param  {string} slug The campaign slug.
 *
 * @returns {Object|null} The global campaign object or null.
 */
export const getCampaign = (slug = '') => {
	const key = `om${slug}`;
	return window[key] ? window[key] : null;
};

/**
 * Get the settings to register the campaign selector block.
 *
 * Because we need to support older versions (<=5.7) this returns the
 * proper settings. Versions >=5.8 use the block.json approach.
 *
 * @since 2.6.10
 *
 * @returns {Object} The settings for the campaign selector block.
 */
export const getBlockSettings = () => {
	const wpVersion = parseFloat(OMAPI.wpVersion);
	const baseSettings = {
		icon: ArchieIcon,
		edit: CampaignSelector,
		save() {
			return null;
		},
	};
	const legacySettings = {
		title: OMAPI.i18n.title,
		description: OMAPI.i18n.description,
		category: 'embed',
		keywords: [
			__('Popup', 'optin-monster-api'),
			__('Form', 'optin-monster-api'),
			__('Campaign', 'optin-monster-api'),
			__('Email', 'optin-monster-api'),
			__('Conversion', 'optin-monster-api'),
		],
		attributes: {
			slug: {
				type: 'string',
			},
			followrules: {
				type: 'boolean',
				default: true,
			},
		},
	};

	return wpVersion >= 5.8 ? baseSettings : Object.assign(baseSettings, legacySettings);
};
