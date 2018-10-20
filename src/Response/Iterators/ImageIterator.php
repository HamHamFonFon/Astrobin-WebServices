<?php

namespace Astrobin\Response\Iterators;

/**
 * Class ImageIterator
 * @package Astrobin\Response
 */
class ImageIterator implements \Iterator
{

    private $var = [];

    /**
     * ImageIterator constructor.
     * @param $array
     */
    public function __construct($array)
    {
        if (is_array($array)) {
            $this->var = $array;
        }
    }


    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->var);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->var);
    }

    /**
     * @return int|mixed|null|string
     */
    public function key()
    {
        return key($this->var);
    }


    /**
     * @return bool
     */
    public function valid()
    {
        $key = key($this->var);
        $var = (!is_null($key) && false !== $key);
        return $var;
    }


    /**
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->var);
    }
}
