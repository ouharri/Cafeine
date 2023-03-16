<?php

namespace Weglot\Client\Api\Shared;

/**
 * Trait AbstractCollectionSerializable
 * @package Weglot\Client\Api\Shared
 */
trait AbstractCollectionIterator
{
    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->collection);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->collection);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return key($this->collection) !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->collection);
    }
}
