<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Identifier;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface IsBinary extends Identifies
{
    /**
     * @param string $bytes
     * @return IsBinary
     */
    public static function fromBytes($bytes);

    /**
     * @return string
     */
    public function toBytes();
}
