<?php

namespace Weglot\Client;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\ResponseInterface;
use Weglot\Client\Api\Exception\ApiError;
use Weglot\Client\Caching\Cache;
use Weglot\Client\Caching\CacheInterface;
use Weglot\Client\HttpClient\ClientInterface;
use Weglot\Client\HttpClient\CurlClient;

/**
 * Class Client
 * @package Weglot\Client
 */
class Client
{
    /**
     * Library version
     *
     * @var string
     */
    const VERSION = '0.5.11';

    /**
     * Weglot API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Weglot settings file Version
     *
     * @var string
     */
    protected $version;

    /**
     * Options for client
     *
     * @var array
     */
    protected $options;

    /**
     * Http Client
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Client constructor.
     * @param string    $apiKey     your Weglot API key
     * @param int       $translationEngine
     * @param string    $version    your settings file version
     * @param array     $options    an array of options, currently only "host" is implemented
     */
    public function __construct($apiKey, $translationEngine, $version = '1', $options = [])
    {
        $this->apiKey = $apiKey;
        $this->version = $version;
        $this->profile = new Profile($apiKey, $translationEngine);

        $this
            ->setHttpClient()
            ->setOptions($options)
            ->setCache();
    }

    /**
     * Creating Guzzle HTTP connector based on $options
     */
    protected function setupConnector()
    {
        $this->httpClient = new CurlClient();
    }

    /**
     * Default options values
     *
     * @return array
     */
    public function defaultOptions()
    {
        return [
            'host'  => 'https://api.weglot.com'
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        // merging default options with user options
        $this->options = array_merge($this->defaultOptions(), $options);
        return $this;
    }

    /**
     * @param null|ClientInterface $httpClient
     * @param null|string $customHeader
     * @return $this
     */
    public function setHttpClient($httpClient = null, $customHeader = null)
    {
        if ($httpClient === null) {
            $httpClient = new CurlClient();

            $header = 'Weglot-Context: PHP\\'.self::VERSION;
            if (!is_null($customHeader)) {
                $header .= ' ' .$customHeader;
            }
            $httpClient->addHeader($header);
        }
        if ($httpClient instanceof ClientInterface) {
            $this->httpClient = $httpClient;
        }
        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param null|CacheInterface $cache
     * @return $this
     */
    public function setCache($cache = null)
    {
        if ($cache === null || !($cache instanceof CacheInterface)) {
            $cache = new Cache();
        }

        $this->cache = $cache;

        return $this;
    }

    /**
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param null|CacheItemPoolInterface $cacheItemPool
     * @return $this
     */
    public function setCacheItemPool($cacheItemPool)
    {
        $this->getCache()->setItemPool($cacheItemPool);

        return $this;
    }

    /**
     * Make the API call and return the response.
     *
     * @param string $method    Method to use for given endpoint
     * @param string $endpoint  Endpoint to hit on API
     * @param array $body       Body content of the request as array
     * @param bool $asArray     To know if we return an array or ResponseInterface
     * @return array|ResponseInterface
     * @throws ApiError
     */
    public function makeRequest($method, $endpoint, $body = [], $asArray = true)
    {
        try {
            if($method === 'GET') {
                $urlParams = array_merge( ['api_key' => $this->apiKey, 'v' => $this->version], $body);
                $body = [];
            }
            else {
                $urlParams = ['api_key' => $this->apiKey, 'v' => $this->version];
            }
            list($rawBody, $httpStatusCode, $httpHeader) = $this->getHttpClient()->request(
                $method,
                $this->makeAbsUrl($endpoint),
                $urlParams,
                $body
            );
            $array = json_decode($rawBody, true);
        } catch (\Exception $e) {
            throw new ApiError($e->getMessage(), $body);
        }

        if ($asArray) {
            return $array;
        }
        return [$rawBody, $httpStatusCode, $httpHeader];
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function makeAbsUrl($endpoint)
    {
        return $this->options['host'] . $endpoint;
    }
}
