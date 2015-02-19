<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Serializer;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface Serializable
{
    /**
     * @param array $data
     * @return Serializable
     */
    public static function deserialize(array $data);

    /**
     * @return array
     */
    public function serialize();
}
