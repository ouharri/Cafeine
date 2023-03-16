<?php

namespace Weglot\Client\Api\Exception;

/**
 * Class MissingWordsOutputException
 * @package Weglot\Client\Api\Exception
 */
class MissingWordsOutputException extends \Exception
{
    /**
     * MissingWordsOutputException constructor.
     * @param array $jsonBody
     */
    public function __construct(array $jsonBody)
    {
        parent::__construct(
            'There is no output words.',
            WeglotCode::GENERIC,
            $jsonBody
        );
    }
}
