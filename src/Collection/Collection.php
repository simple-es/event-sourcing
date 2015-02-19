<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Collection;

use SimpleES\EventSourcing\Exception\Exception;
use SimpleES\EventSourcing\Exception\ObjectIsImmutable;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
abstract class Collection implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $position;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $this->guardItem($item);

            $this->items[] = $item;
        }

        $this->guardAmountOfItems(count($this->items));

        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (!isset($this->items[$offset])) {
            return null;
        }

        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     * @throws ObjectIsImmutable
     */
    public function offsetSet($offset, $value)
    {
        throw ObjectIsImmutable::create($this, 'offsetSet');
    }

    /**
     * {@inheritdoc}
     * @throws ObjectIsImmutable
     */
    public function offsetUnset($offset)
    {
        throw ObjectIsImmutable::create($this, 'offsetUnset');
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->items[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @param mixed $item
     * @return void
     * @throws Exception
     */
    abstract protected function guardItem($item);

    /**
     * @param int $amount
     * @return void
     * @throws Exception
     */
    abstract protected function guardAmountOfItems($amount);
}
