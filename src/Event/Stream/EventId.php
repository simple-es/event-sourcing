<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Stream;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $string
     * @return EventId
     */
    public static function fromString($string)
    {
        return new EventId($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * @param EventId $other
     * @return bool
     */
    public function equals(EventId $other)
    {
        return ($other->id === $this->id);
    }

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->id = (string)$id;
    }
}
