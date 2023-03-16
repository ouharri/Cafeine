<?php

namespace Weglot\Util\Regex;

/**
 * Class RegexEnum
 * @package Weglot\Util
 */
abstract class RegexEnum
{

    /**
	 * @var string
	 */
	const START_WITH = 'START_WITH';

	/**
	 * @var string
	 */
	const END_WITH = 'END_WITH';

	/**
	 * @var string
	 */
	const CONTAIN = 'CONTAIN';

	/**
	 * @var string
	 */
	const IS_EXACTLY = 'IS_EXACTLY';

	/**
	 * @var string
	 */
	const MATCH_REGEX = 'MATCH_REGEX';


}
