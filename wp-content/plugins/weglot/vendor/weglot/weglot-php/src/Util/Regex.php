<?php

namespace Weglot\Util;

use Weglot\Util\Regex\RegexEnum;

/**
 * Class Regex
 * @package Weglot\Util
 */
class Regex {
	/**
	 * @param string $type
	 * @param string $value
	 */
	public function __construct( $type, $value ) {
		$this->type  = $type;
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getRegex() {
		$str = null;
		switch ($this->type) {
			case RegexEnum::START_WITH:
				$str = sprintf('^%s', $this->value);
				break;
			case RegexEnum::END_WITH:
				$str = sprintf('%s$', $this->value);
				break;
			case RegexEnum::CONTAIN:
				$str = sprintf('%s', $this->value);
				break;
			case RegexEnum::IS_EXACTLY:
				$str = sprintf('^%s$', $this->value);
				break;
			case RegexEnum::MATCH_REGEX:
				return $this->value;
		}

		if ( null === $str) {
			return $this->value;
		}

		return $str;
	}
}
