<?php

namespace Weglot\Client\Api\Exception;

/**
 * Class MissingRequiredParamException
 * @package Weglot\Client\Api\Exception
 */
class MissingRequiredParamException extends AbstractException
{
    /**
     * MissingRequiredParamException constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'Required fields for $params are: language_from, language_to, bot, request_url.',
            WeglotCode::PARAMETERS
        );
    }
}
