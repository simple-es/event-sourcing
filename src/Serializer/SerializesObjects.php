<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Serializer;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface SerializesObjects
{
    /**
     * @param mixed $data
     * @return string
     */
    public function serialize($data);

    /**
     * @param string $data
     * @return mixed
     */
    public function deserialize($data);
}
