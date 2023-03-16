<?php

namespace Weglot\Client\Endpoint;

use Weglot\Client\Api\Exception\ApiError;

/**
 * Class Status
 * @package Weglot\Client\Endpoint
 */
class Status extends Endpoint
{
    const METHOD = 'GET';
    const ENDPOINT = '/status';

    /**
     * @return bool
     * @throws ApiError
     */
    public function handle()
    {
        list($rawBody, $httpStatusCode, $httpHeader) = $this->request([], false);

        if ($httpStatusCode === 200) {
            return true;
        }
        return false;
    }
}
