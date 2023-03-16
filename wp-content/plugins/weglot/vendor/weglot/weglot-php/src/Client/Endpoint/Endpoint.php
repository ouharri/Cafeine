<?php

namespace Weglot\Client\Endpoint;

use Psr\Http\Message\ResponseInterface;
use Weglot\Client\Api\Exception\ApiError;
use Weglot\Client\Caching\CacheInterface;
use Weglot\Client\Client;
use Weglot\Client\Caching\Cache;

/**
 * Class Endpoint
 * @package Weglot\Client\Endpoint
 */
abstract class Endpoint
{
    const METHOD = 'GET';
    const ENDPOINT = '/';

    /**
     * @var Client
     */
    protected $client;

    /**
     * Endpoint constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * @param Client $client
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->getClient()->getCache();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        $parentClass = \get_called_class();
        return $parentClass::ENDPOINT;
    }

    /**
     * Used to run endpoint onto given Client
     */
    abstract public function handle();

    /**
     * @param array $body
     * @param bool $ignoreCache
     * @param bool $asArray
     * @return array|ResponseInterface
     * @throws ApiError
     */
    protected function request(array $body = [], $asArray = true)
    {
        $parentClass = \get_called_class();
        $response = $this->getClient()->makeRequest($parentClass::METHOD, $parentClass::ENDPOINT, $body, $asArray);

        return $response;
    }
}
