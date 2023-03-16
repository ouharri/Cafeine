<?php

namespace Weglot\Client\Api\Exception;

use Exception;

/**
 * Class AbstractException
 * @package Weglot\Client\Api\Exception
 */
abstract class AbstractException extends Exception
{
    /**
     * @var int
     */
    protected $weglotCode;

    /**
     * @var array
     */
    protected $jsonBody;

    /**
     * AbstractException constructor.
     * @param string $message
     * @param int $weglotCode
     * @param array $jsonBody
     */
    public function __construct($message, $weglotCode = WeglotCode::GENERIC, $jsonBody = [])
    {
        parent::__construct($message);

        $this->weglotCode = $weglotCode;
        $this->jsonBody = $jsonBody;
    }

    /**
     * @return int
     */
    public function getWeglotCode()
    {
        return $this->weglotCode;
    }

    /**
     * @return array
     */
    public function getJsonBody()
    {
        return $this->jsonBody;
    }
}
