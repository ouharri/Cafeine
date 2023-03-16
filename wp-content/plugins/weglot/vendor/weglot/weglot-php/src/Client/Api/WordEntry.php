<?php

namespace Weglot\Client\Api;

use Weglot\Client\Api\Enum\WordType;
use Weglot\Client\Api\Exception\InvalidWordTypeException;
use Weglot\Client\Api\Shared\AbstractCollectionEntry;

/**
 * Class WordEntry
 * @package Weglot\Client\Api
 */
class WordEntry extends AbstractCollectionEntry
{
    /**
     * @var string
     */
    protected $word;

    /**
     * @var int
     */
    protected $type = WordType::TEXT;

    /**
     * WordEntry constructor.
     * @param $word
     * @param int $type
     * @throws InvalidWordTypeException
     */
    public function __construct($word, $type = WordType::TEXT)
    {
        $this->setWord($word)
            ->setType($type);
    }

    /**
     * @param string $word
     * @return $this
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set type of word you gonna translate.
     * Returns false if type is incorrect.
     *
     * @param $type
     * @return $this
     * @throws InvalidWordTypeException
     */
    public function setType($type)
    {
        /**
         * Thoses WordType::__MIN and WordType::__MAX values are
         * only used to check if given type is okay according to
         * what we have in WordType.
         *
         * @see src/Client/Api/Enum/WordType.php
         */
        if (!($type >= WordType::__MIN && $type <= WordType::__MAX)) {
            throw new InvalidWordTypeException();
        }
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            't' => $this->getType(),
            'w' => $this->getWord()
        ];
    }
}
