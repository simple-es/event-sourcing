<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate\Manager;

use SimpleES\EventSourcing\Aggregate\Repository\Repository;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\IdentityMap\MapsIdentity;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateManager implements ManagesAggregates
{
    /**
     * @var MapsIdentity
     */
    private $identityMap;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param MapsIdentity $identityMap
     * @param Repository   $repository
     */
    public function __construct(MapsIdentity $identityMap, Repository $repository)
    {
        $this->identityMap = $identityMap;
        $this->repository  = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TracksEvents $aggregate)
    {
        if (!$this->identityMap->contains($aggregate->aggregateId())) {
            $this->identityMap->add($aggregate);
        }

        $this->repository->add($aggregate);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Identifies $aggregateId)
    {
        if ($this->identityMap->contains($aggregateId)) {
            return $this->identityMap->get($aggregateId);
        }

        $aggregate = $this->repository->get($aggregateId);

        $this->identityMap->add($aggregate);

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->identityMap->clear();
    }
}
