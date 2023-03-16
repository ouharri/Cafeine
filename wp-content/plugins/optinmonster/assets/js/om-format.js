/* ==========================================================
 * optinmonster-format.js
 * ==========================================================
 * Copyright 2021 Awesome Motive.
 * https://awesomemotive.com
 * ========================================================== */

'use strict';

import MonsterLinkFormat from './Components/Formats/MonsterLink';

wp.richText.registerFormatType('optinmonster/om-format', {
	title: OMAPI.i18n.open_popup,
	tagName: 'a',
	className: 'om-format',
	attributes: {
		url: 'href',
		target: 'target',
		rel: 'rel',
		'data-slug': 'data-slug',
	},
	edit: MonsterLinkFormat,
});
