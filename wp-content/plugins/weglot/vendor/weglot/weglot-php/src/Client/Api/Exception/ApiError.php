<?php

namespace Weglot\Client\Api\Exception;

/**
 * Class ApiError
 * @package Weglot\Client\Api\Exception
 */
class ApiError extends AbstractException
{
    /**
     * ApiError constructor.
     * @param $message
     * @param array $jsonBody
     */
    public function __construct($message, array $jsonBody = [])
    {
        parent::__construct($message, WeglotCode::AUTH, $jsonBody);
    }
}
