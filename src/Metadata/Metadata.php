<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Metadata;

use SimpleES\EventSourcing\Exception\ObjectIsImmutable;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class Metadata implements \ArrayAccess
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param Metadata $other
     * @return Metadata
     */
    public function merge(Metadata $other)
    {
        return new Metadata(
            array_merge($this->data, $other->data)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (!isset($this->data[$offset])) {
            return null;
        }

        return $this->data[$offset];
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
        throw ObjectIsImmutable::create($this, 'offsetSet');
    }
}
