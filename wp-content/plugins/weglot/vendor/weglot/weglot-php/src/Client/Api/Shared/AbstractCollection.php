<?php

namespace Weglot\Client\Api\Shared;

use Countable;
use Iterator;
use ArrayAccess;
use JsonSerializable;

/**
 * Class AbstractCollection
 * @package Weglot\Client\Api\Shared
 */
abstract class AbstractCollection implements Countable, Iterator, ArrayAccess, JsonSerializable, AbstractCollectionInterface
{
    use AbstractCollectionCountable;
    use AbstractCollectionArrayAccess;
    use AbstractCollectionSerializable;
    use AbstractCollectionIterator;

    /**
     * @var AbstractCollectionEntry[]
     */
    protected $collection = [];

    /**
     * @param AbstractCollectionEntry $entry
     * @return $this
     */
    public function addOne(AbstractCollectionEntry $entry)
    {
        $this->collection[] = $entry;

        return $this;
    }

    /**
     * @param array $entries
     * @return $this
     */
    public function addMany(array $entries)
    {
        foreach ($entries as $entry) {
            $this->addOne($entry);
        }

        return $this;
    }
}
