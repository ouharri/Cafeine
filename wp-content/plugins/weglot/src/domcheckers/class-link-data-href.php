<?php
namespace WeglotWP\Domcheckers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Check\Dom\LinkHref;


class Link_Data_Href extends LinkHref {
	/**
	 * {@inheritdoc}
	 */
	const PROPERTY = 'data-href';
}
