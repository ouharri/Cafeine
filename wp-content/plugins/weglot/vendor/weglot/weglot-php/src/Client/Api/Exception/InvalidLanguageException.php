<?php

namespace Weglot\Client\Api\Exception;

/**
 * Class InvalidLanguageException
 * @package Weglot\Client\Api\Exception
 */
class InvalidLanguageException extends AbstractException
{
    /**
     * InvalidLanguageException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'The given language is invalid.',
            WeglotCode::PARAMETERS
        );
    }
}
