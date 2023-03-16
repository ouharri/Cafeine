<?php

namespace Weglot\Client\HttpClient;

interface ClientInterface
{
    /**
     * @param string $service
     * @param string $value
     * @return void
     */
    public function addUserAgentInfo($service, $value);

    /**
     * @return array
     */
    public function getUserAgentInfo();

    /**
     * @param string $header
     * @return void
     */
    public function addHeader($header);

    /**
     * @return array
     */
    public function getDefaultHeaders();

    /**
     * @param string $method The HTTP method being used
     * @param string $absUrl The URL being requested, including domain and protocol
     * @param array $params KV pairs for parameters.
     * @param array $body JSON body content (as array)
     * @return [$rawBody, $httpStatusCode, $httpHeader]
     */
    public function request($method, $absUrl, $params = [], $body = []);
}
