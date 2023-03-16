<?php

namespace Weglot\Client\Api;

use Weglot\Client\Api\Shared\AbstractCollection;
use Weglot\Client\Api\Shared\AbstractCollectionEntry;

/**
 * Class LanguageCollection
 * @package Weglot\Client\Api
 */
class LanguageCollection extends AbstractCollection
{
    /**
     * @param AbstractCollectionEntry $entry
     * @return $this
     */
    public function addOne(AbstractCollectionEntry $entry)
    {
        $this->collection[$entry->getInternalCode()] = $entry;

        return $this;
    }

    /**
     * @param string $iso639    ISO 639-1 code to identify language
     * @return LanguageEntry|null
     */
    public function getCode($iso639)
    {
        if (isset($iso639)) {
            return $this[$iso639];
        }
        return null;
    }
}
