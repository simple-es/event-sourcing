<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Basket;

use SimpleES\EventSourcing\Aggregate\Identifier\AggregateIdentifyingCapabilities;
use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class BasketId implements IdentifiesAggregate
{
    use AggregateIdentifyingCapabilities;
}
