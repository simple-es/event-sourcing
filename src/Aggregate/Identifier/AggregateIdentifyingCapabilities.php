<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate\Identifier;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
trait AggregateIdentifyingCapabilities
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $string
     * @return static
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * @param IdentifiesAggregate $other
     * @return bool
     */
    public function equals(IdentifiesAggregate $other)
    {
        return ($other instanceof static && $other->id === $this->id);
    }

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->id = (string)$id;
    }
}
