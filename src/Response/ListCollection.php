<?php

namespace AstrobinWs\Response;

use Astrobin\Response\AstrobinResponse;
use AstrobinWs\Response\Iterators\CollectionIterator;

/**
 * Class ListCollection
 * @package Astrobin\Response
 */
final class ListCollection extends AbstractResponse implements \IteratorAggregate, AstrobinResponse
{

    public $listCollection;

    /**
     * @return CollectionIterator|\Traversable
     */
    public function getIterator(): CollectionIterator
    {
        return new CollectionIterator($this->listCollection);
    }

    /**
     * @param $collection
     */
    public function add($collection): void
    {
        $this->listCollection[] = $collection;
    }
}
