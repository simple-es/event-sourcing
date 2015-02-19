<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\UnitOfWork;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Exception\DuplicateAggregateFound;
use SimpleES\EventSourcing\Repository\Repository;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class UnitOfWork implements TracksAggregates
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var TracksEvents[]
     */
    private $identityMap;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository  = $repository;
        $this->identityMap = [];
    }

    /**
     * {@inheritdoc}
     */
    public function track(TracksEvents $aggregate)
    {
        $lookupKey = $this->createLookupKey($aggregate->aggregateId());

        if (isset($this->identityMap[$lookupKey])) {
            throw DuplicateAggregateFound::create($aggregate->aggregateId());
        }

        $this->identityMap[$lookupKey] = $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function find(IdentifiesAggregate $aggregateId)
    {
        if ($this->inIdentityMap($aggregateId)) {
            return $this->getFromIdentityMap($aggregateId);
        }

        $aggregate = $this->findInRepository($aggregateId);

        $this->track($aggregate);

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        /** @var TracksEvents $aggregate */
        foreach ($this->identityMap as $aggregate) {
            if ($aggregate->hasRecordedEvents()) {
                $this->repository->add($aggregate);
            }
        }
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return bool
     */
    private function inIdentityMap(IdentifiesAggregate $aggregateId)
    {
        $lookupKey = $this->createLookupKey($aggregateId);

        return isset($this->identityMap[$lookupKey]);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    private function getFromIdentityMap(IdentifiesAggregate $aggregateId)
    {
        $lookupKey = $this->createLookupKey($aggregateId);

        return $this->identityMap[$lookupKey];
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    private function findInRepository(IdentifiesAggregate $aggregateId)
    {
        return $this->repository->find($aggregateId);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return string
     */
    private function createLookupKey(IdentifiesAggregate $aggregateId)
    {
        return sprintf('%s(%s)', get_class($aggregateId), (string)$aggregateId);
    }
}
