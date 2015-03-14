<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Basket;

use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Identifier\IdentifyingCapabilities;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class BasketId implements Identifies
{
    use IdentifyingCapabilities;
}
