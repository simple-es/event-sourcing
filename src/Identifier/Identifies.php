<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Identifier;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface Identifies
{
    /**
     * @param string $string
     * @return Identifies
     */
    public static function fromString($string);

    /**
     * @return string
     */
    public function __toString();

    /**
     * @param Identifies $other
     * @return bool
     */
    public function equals(Identifies $other);
}
