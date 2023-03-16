<?php

namespace Weglot\Client\Api\Shared;

/**
 * Interface AbstractCollectionInterface
 * @package Weglot\Client\Api\Shared
 */
interface AbstractCollectionInterface
{
    /**
     * Add one word at a time
     *
     * @param AbstractCollectionEntry $entry
     */
    public function addOne(AbstractCollectionEntry $entry);

    /**
     * Add several words at once
     *
     * @param AbstractCollectionEntry[] $entries
     */
    public function addMany(array $entries);
}
