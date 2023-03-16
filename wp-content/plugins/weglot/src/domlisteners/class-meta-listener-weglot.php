<?php

namespace WeglotWP\Domlisteners;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Parser\Listener\AbstractCrawlerAfterListener;
use Weglot\Parser\Parser;
use Weglot\Client\Api\Enum\WordType;


/**
 * @since 2.0
 */
final class Meta_Listener_Weglot extends AbstractCrawlerAfterListener {
	protected $attributes = array(
		'name' => array(
			'twitter:image',
			'twitter:card',
			'twitter:site',
			'twitter:creator',
		),
	);

	/**
	 * {@inheritdoc}
	 */
	protected function xpath() {
		$selectors = array();
		foreach ( $this->attributes as $name => $values ) {
			foreach ( $values as $value ) {
				$selectors[] = '@' . $name . ' = \'' . $value . '\'';
			}
		}
		return '//meta[(' . implode( ' or ', $selectors ) . ') and not(ancestor-or-self::*[@' . Parser::ATTRIBUTE_NO_TRANSLATE . '])]/@content';
	}
	/**
	 * {@inheritdoc}
	 */
	protected function type( \DOMNode $node ) {
		return WordType::META_CONTENT;
	}
}
