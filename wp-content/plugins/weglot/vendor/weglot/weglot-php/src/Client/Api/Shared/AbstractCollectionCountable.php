<?php

namespace Weglot\Client\Api\Shared;

/**
 * Trait AbstractCollectionCountable
 * @package Weglot\Client\Api\Shared
 */
trait AbstractCollectionCountable
{
    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->collection);
    }
}
