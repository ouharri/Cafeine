<?php

namespace Weglot\Client\Api\Exception;

/**
 * Class InvalidWordTypeException
 * @package Weglot\Client\Api\Exception
 */
class InvalidWordTypeException extends AbstractException
{
    /**
     * InvalidWordTypeException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'The given WordType is invalid.',
            WeglotCode::PARAMETERS
        );
    }
}
