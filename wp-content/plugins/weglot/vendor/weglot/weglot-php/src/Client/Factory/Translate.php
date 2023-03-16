<?php

namespace Weglot\Client\Factory;

use Weglot\Client\Api\Exception\InputAndOutputCountMatchException;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\Exception\MissingRequiredParamException;
use Weglot\Client\Api\Exception\MissingWordsOutputException;
use Weglot\Client\Api\TranslateEntry;
use Weglot\Client\Api\WordEntry;

/**
 * Class Translate
 * @package Weglot\Client\Factory
 */
class Translate
{
    /**
     * @var array
     */
    protected $response = [];

    /**
     * Translate constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->setResponse($response);
    }

    /**
     * @param array $response
     * @return $this
     */
    public function setResponse(array $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return TranslateEntry
     * @throws InputAndOutputCountMatchException
     * @throws InvalidWordTypeException
     * @throws MissingRequiredParamException
     * @throws MissingWordsOutputException
     */
    public function handle()
    {
        $response = $this->getResponse();

        $params = [
            'language_from' => $response['l_from'],
            'language_to' => $response['l_to'],
            'bot' => $response['bot'],
            'request_url' => $response['request_url'],
            'title' => $response['title']
        ];
        $translate = new TranslateEntry($params);

        if (!isset($response['to_words'])) {
            throw new MissingWordsOutputException($response);
        }
        if (count($response['from_words']) !== count($response['to_words'])) {
            throw new InputAndOutputCountMatchException($response);
        }

        for ($i = 0; $i < \count($response['from_words']); ++$i) {
            $translate->getInputWords()->addOne(new WordEntry($response['from_words'][$i]));
        }
        for ($i = 0; $i < \count($response['to_words']); ++$i) {
            $translate->getOutputWords()->addOne(new WordEntry($response['to_words'][$i]));
        }

        return $translate;
    }
}
