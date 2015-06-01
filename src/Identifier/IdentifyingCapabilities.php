<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Identifier;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
trait IdentifyingCapabilities
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $string
     * @return Identifies
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other)
    {
        return $other == $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->id = (string) $id;
    }
}
