<?php

namespace Weglot\Client\Endpoint;

use Weglot\Client\Api\Exception\ApiError;
use Weglot\Client\Api\Exception\InputAndOutputCountMatchException;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\Exception\MissingRequiredParamException;
use Weglot\Client\Api\Exception\MissingWordsOutputException;
use Weglot\Client\Api\TranslateEntry;
use Weglot\Client\Client;
use Weglot\Client\Factory\Translate as TranslateFactory;

/**
 * Class Translate
 * @package Weglot\Client\Endpoint
 */
class CdnTranslate extends Endpoint
{
    const METHOD = 'POST';
    const ENDPOINT = '/translate';

    /**
     * @var TranslateEntry
     */
    protected $translateEntry;

    /**
     * Translate constructor.
     * @param TranslateEntry $translateEntry
     * @param Client $client
     */
    public function __construct(TranslateEntry $translateEntry, Client $client)
    {
        $this->setTranslateEntry($translateEntry);
        $currentHost = $client->getOptions()['host'];
        if($currentHost) {
            $cdnHost = str_replace('https://api.weglot.' , 'https://cdn-api-weglot.' , $currentHost);
            $client->setOptions(array('host' => $cdnHost ));
        }
        parent::__construct($client);
    }

    /**
     * @return TranslateEntry
     */
    public function getTranslateEntry()
    {
        return $this->translateEntry;
    }

    /**
     * @param TranslateEntry $translateEntry
     * @return $this
     */
    public function setTranslateEntry(TranslateEntry $translateEntry)
    {
        $this->translateEntry = $translateEntry;

        return $this;
    }

    /**
     * @return TranslateEntry
     * @throws ApiError
     * @throws InputAndOutputCountMatchException
     * @throws InvalidWordTypeException
     * @throws MissingRequiredParamException
     * @throws MissingWordsOutputException
     */
    public function handle()
    {
        $asArray = $this->translateEntry->jsonSerialize();
        if (!empty($asArray['words'])) {
            list($rawBody, $httpStatusCode) = $this->request($asArray, false);
            if ($httpStatusCode !== 200) {
                throw new ApiError($rawBody, $asArray);
            }
            $response = json_decode($rawBody, true);
        }
        else {
            throw new ApiError("Empty words passed", $asArray);
        }

        $factory = new TranslateFactory($response);
        return $factory->handle();
    }
}
