<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Identifier;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface CreatesIdentifiers
{
    /**
     * @return Identifies
     */
    public function generate();

    /**
     * @param string $string
     * @return Identifies
     */
    public function fromString($string);
}
