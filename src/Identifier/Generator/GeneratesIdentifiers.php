<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Identifier\Generator;

use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
interface GeneratesIdentifiers
{
    /**
     * @return Identifies
     */
    public function generateIdentifier();
}
