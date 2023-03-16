<?php

namespace Weglot\Client\Api\Shared;

/**
 * Trait AbstractCollectionArrayAccess
 * @package Weglot\Client\Api\Shared
 */
trait AbstractCollectionArrayAccess
{
    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (isset($this->collection[$offset]) && $value instanceof AbstractCollectionEntry) {
            $this->collection[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }
}
