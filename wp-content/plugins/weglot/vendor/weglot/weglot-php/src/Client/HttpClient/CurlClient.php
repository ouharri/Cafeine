<?php

namespace Weglot\Client\HttpClient;

// @codingStandardsIgnoreStart
// PSR2 requires all constants be upper case. Sadly, the CURL_SSLVERSION
// constants do not abide by those rules.
// Note the values 1 and 6 come from their position in the enum that
// defines them in cURL's source code.
if (!\defined('CURL_SSLVERSION_TLSv1')) {
    define('CURL_SSLVERSION_TLSv1', 1);
}
if (!\defined('CURL_SSLVERSION_TLSv1_2')) {
    define('CURL_SSLVERSION_TLSv1_2', 6);
}
// @codingStandardsIgnoreEnd

class CurlClient implements ClientInterface
{
    const DEFAULT_TIMEOUT = 80;
    const DEFAULT_CONNECT_TIMEOUT = 30;

    const INITIAL_NETWORK_RETRY_DELAY = 0.5;
    const MAX_NETWORK_RETRY_DELAY = 2.0;

    const MAX_NETWORK_RETRIES = 0;

    /**
     * @var int
     */
    protected $timeout = self::DEFAULT_TIMEOUT;

    /**
     * @var int
     */
    protected $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;

    /**
     * @var int
     */
    protected $maxNetworkRetries = self::MAX_NETWORK_RETRIES;

    /**
     * @var array
     */
    protected $defaultHeaders = [];

    /**
     * @var array
     */
    protected $defaultOptions = [];

    /**
     * @var array
     */
    protected $userAgentInfo = [];

    /**
     * CurlClient constructor.
     * @param array $defaultOptions
     * @param array $defaultHeaders
     */
    public function __construct(array $defaultOptions = [], array $defaultHeaders = [])
    {
        $this->defaultOptions = $defaultOptions;
        $this->defaultHeaders = $defaultHeaders;

        $this->initUserAgentInfo();
    }

    /**
     * Initializing default user-agent
     */
    public function initUserAgentInfo()
    {
        $curlVersion = curl_version();
        $this->userAgentInfo = [
            'curl' =>  'cURL\\' .$curlVersion['version'],
            'ssl' => $curlVersion['ssl_version']
        ];
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->defaultOptions;
    }

    /**
     * @param string $header
     * @return void
     */
    public function addHeader($header)
    {
        $this->defaultHeaders[] = $header;
    }

    /**
     * @return array
     */
    public function getDefaultHeaders()
    {
        return $this->defaultHeaders;
    }

    /**
     * @param string $service
     * @param string $value
     * @return void
     */
    public function addUserAgentInfo($service, $value)
    {
        $this->userAgentInfo[$service] = $value;
    }

    /**
     * @return array
     */
    public function getUserAgentInfo()
    {
        return $this->userAgentInfo;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function setTimeout($seconds)
    {
        $this->timeout = $seconds;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @param int $seconds
     * @return $this
     */
    public function setConnectTimeout($seconds)
    {
        $this->connectTimeout = $seconds;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxNetworkRetries()
    {
        return $this->maxNetworkRetries;
    }

    /**
     * @param int $retries
     * @return $this
     */
    public function setMaxNetworkRetries($retries)
    {
        $this->maxNetworkRetries = $retries;
        return $this;
    }

    /**
     * @param string $method The HTTP method being used
     * @param string $absUrl The URL being requested, including domain and protocol
     * @param array $params KV pairs for parameters.
     * @param array $body JSON body content (as array)
     * @return [$rawBody, $httpStatusCode, $httpHeader]
     * @throws \Exception
     */
    public function request($method, $absUrl, $params = [], $body = [])
    {
        // init
        $method = strtolower($method);
        $headers = $this->getDefaultHeaders();
        $options = $this->getDefaultOptions();

        // parameters
        if (\count($params) > 0) {
            $encoded = http_build_query($params);
            $absUrl = $absUrl . '?' .$encoded;
        }

        // generic processing
        list($options, $headers) = $this->processMethod($method, $options, $headers, $body);
        $options = $this->processHeadersAndOptions($headers, $options, $absUrl);

        // Create a callback to capture HTTP headers for the response
        $rheaders = [];
        $headerCallback = function ($curl, $header_line) use (&$rheaders) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($header_line, ":") === false) {
                return \strlen($header_line);
            }
            list($key, $value) = explode(":", trim($header_line), 2);
            $rheaders[trim($key)] = trim($value);
            return \strlen($header_line);
        };
        $options[CURLOPT_HEADERFUNCTION] = $headerCallback;

        list($rbody, $rcode) = $this->executeRequestWithRetries($options, $absUrl);

        return [$rbody, $rcode, $rheaders];
    }

    /**
     * Setup behavior for each methods
     * @param string $method
     * @param array $options
     * @param array $headers
     * @param array $body
     * @return [$options, $headers]
     * @throws \Exception
     */
    private function processMethod($method, array $options, array $headers, array $body = [])
    {
        if ($method === 'get') {
            if ($body !== []) {
                throw new \Exception('Issuing a GET request with a body');
            }
            $options[CURLOPT_HTTPGET] = 1;
        } elseif ($method === 'post') {
            $data_string = json_encode($body);

            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $data_string;

            array_push($headers, 'Content-Type: application/json');
            array_push($headers, 'Content-Length: ' . strlen($data_string));
        } else {
            throw new \Exception('Unrecognized method ' . strtoupper($method));
        }

        return [$options, $headers];
    }

    /**
     * @param array $headers
     * @param array $options
     * @param string $absUrl
     * @return array
     */
    private function processHeadersAndOptions(array $headers, array $options, $absUrl)
    {
        // By default for large request body sizes (> 1024 bytes), cURL will
        // send a request without a body and with a `Expect: 100-continue`
        // header, which gives the server a chance to respond with an error
        // status code in cases where one can be determined right away (say
        // on an authentication problem for example), and saves the "large"
        // request body from being ever sent.
        //
        // Unfortunately, the bindings don't currently correctly handle the
        // success case (in which the server sends back a 100 CONTINUE), so
        // we'll error under that condition. To compensate for that problem
        // for the time being, override cURL's behavior by simply always
        // sending an empty `Expect:` header.
        array_push($headers, 'Expect: ');

        // injecting user-agent in headers
        array_push($headers, 'User-Agent: ' . implode(' | ', $this->getUserAgentInfo()));

        // options
        $options[CURLOPT_URL] = $absUrl;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_CONNECTTIMEOUT] = $this->getConnectTimeout();
        $options[CURLOPT_TIMEOUT] = $this->getTimeout();
        $options[CURLOPT_HTTPHEADER] = $headers;
        $options[CURLOPT_SSL_VERIFYPEER] = true;
        $options[CURLOPT_CAPATH] = __DIR__ . '/../../../data/';
        $options[CURLOPT_CAINFO] = __DIR__ . '/../../../data/ca-certificates.crt';

        return $options;
    }

    /**
     * @param array $options cURL options
     * @param string $absUrl The URL being requested, including domain and protocol
     * @return array
     * @throws \Exception
     */
    protected function executeRequestWithRetries(array $options, $absUrl)
    {
        $numRetries = 0;

        while (true) {
            $rcode = $errno = 0;

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $rbody = curl_exec($curl);

            if ($rbody === false) {
                $errno = curl_errno($curl);
                $message = curl_error($curl);
            } else {
                $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            }
            curl_close($curl);

            if ($this->shouldRetry($errno, $rcode, $numRetries)) {
                $numRetries += 1;
                $sleepSeconds = $this->sleepTime($numRetries);
                usleep(\intval($sleepSeconds * 1000000));
            } else {
                break;
            }
        }

        if ($rbody === false) {
            $this->handleCurlError($absUrl, $errno, $message, $numRetries);
        }

        return [$rbody, $rcode];
    }

    /**
     * @param string $url
     * @param int $errno
     * @param string $message
     * @param int $numRetries
     * @throws \Exception
     */
    private function handleCurlError($url, $errno, $message, $numRetries)
    {
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Weglot ($url).  Please check your "
                    . "internet connection and try again.  If this problem persists, "
                    . "you should check Weglot's status at "
                    . "https://twitter.com/weglot, or";
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify Weglot's SSL certificate.  Please make sure "
                    . "that your network is not intercepting certificates.  "
                    . "(Try going to $url in your browser.)  "
                    . "If this problem persists,";
                break;
            default:
                $msg = "Unexpected error communicating with Weglot.  "
                    . "If this problem persists,";
        }
        $msg .= " let us know at support@weglot.com.\n\n(Network error [errno $errno]: $message)";
        if ($numRetries > 0) {
            $msg .= "\n\nRequest was retried $numRetries times.";
        }
        throw new \Exception($msg);
    }

    /**
     * Checks if an error is a problem that we should retry on. This includes both
     * socket errors that may represent an intermittent problem and some special
     * HTTP statuses.
     * @param int $errno
     * @param int $rcode
     * @param int $numRetries
     * @return bool
     */
    private function shouldRetry($errno, $rcode, $numRetries)
    {
        // Don't make too much retries
        if ($numRetries >= $this->getMaxNetworkRetries()) {
            return false;
        }

        // Retry on timeout-related problems (either on open or read).
        $timeoutRelated = ($errno === CURLE_OPERATION_TIMEOUTED);

        // Destination refused the connection, the connection was reset, or a
        // variety of other connection failures. This could occur from a single
        // saturated server, so retry in case it's intermittent.
        $refusedConnection = ($errno === CURLE_COULDNT_CONNECT);

        // 409 conflict
        $conflict = ($rcode === 409);

        if ($timeoutRelated || $refusedConnection || $conflict) {
            return true;
        }
        return false;
    }


    /**
     * @param int $numRetries
     * @return float
     */
    private function sleepTime($numRetries)
    {
        // Apply exponential backoff with $initialNetworkRetryDelay on the
        // number of $numRetries so far as inputs. Do not allow the number to exceed
        // $maxNetworkRetryDelay.
        $sleepSeconds = min(
            self::INITIAL_NETWORK_RETRY_DELAY * 1.0 * pow(2, $numRetries - 1),
            self::MAX_NETWORK_RETRY_DELAY
        );
        // Apply some jitter by randomizing the value in the range of
        // ($sleepSeconds / 2) to ($sleepSeconds).
        $sleepSeconds *= 0.5 * (1 + (mt_rand() / mt_getrandmax() * 1.0));
        // But never sleep less than the base sleep seconds.
        $sleepSeconds = max(self::INITIAL_NETWORK_RETRY_DELAY, $sleepSeconds);
        return $sleepSeconds;
    }
}
