<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate\Manager;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\IdentityMap\MapsIdentity;
use SimpleES\EventSourcing\Repository\Repository;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class AggregateManager implements ManagesAggregates
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
    public function save(TracksEvents $aggregate)
    {
        if (!$this->identityMap->contains($aggregate->aggregateId())) {
            $this->identityMap->add($aggregate);
        }

        $this->repository->save($aggregate);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(IdentifiesAggregate $aggregateId)
    {
        if ($this->identityMap->contains($aggregateId)) {
            $this->identityMap->get($aggregateId);
        }

        $this->repository->fetch($aggregateId);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->identityMap->clear();
    }
}
