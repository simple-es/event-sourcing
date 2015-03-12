<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test;

use SimpleES\EventSourcing\Aggregate\AggregateHistory;
use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\Stream\EventEnvelope;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Timestamp\Timestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class TestHelper
{
    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private $testCase;

    private $domainEvents;
    private $domainEventsEventOne;
    private $domainEventsEventTwo;
    private $domainEventsEventThree;

    private $aggregateHistory;
    private $aggregateHistoryEventOne;
    private $aggregateHistoryEventTwo;
    private $aggregateHistoryEventThree;

    private $eventStream;
    private $eventStreamEnvelopeOne;
    private $eventStreamEnvelopeTwo;
    private $eventStreamEnvelopeThree;
    private $eventStreamEventOne;
    private $eventStreamEventTwo;
    private $eventStreamEventThree;

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(\PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function tearDown()
    {
        $this->domainEvents           = null;
        $this->domainEventsEventOne   = null;
        $this->domainEventsEventTwo   = null;
        $this->domainEventsEventThree = null;

        $this->aggregateHistory           = null;
        $this->aggregateHistoryEventOne   = null;
        $this->aggregateHistoryEventTwo   = null;
        $this->aggregateHistoryEventThree = null;

        $this->eventStream              = null;
        $this->eventStreamEnvelopeOne   = null;
        $this->eventStreamEnvelopeTwo   = null;
        $this->eventStreamEnvelopeThree = null;
        $this->eventStreamEventOne      = null;
        $this->eventStreamEventTwo      = null;
        $this->eventStreamEventThree    = null;
    }

    /**
     * @param IdentifiesAggregate $id
     * @return DomainEvents
     */
    public function getDomainEvents(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->domainEvents[$key])) {
            $this->domainEvents[$key] = new DomainEvents(
                [
                    $this->getDomainEventsEventOne($id),
                    $this->getDomainEventsEventTwo($id),
                    $this->getDomainEventsEventThree($id)
                ]
            );
        }

        return $this->domainEvents[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDomainEventsEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->domainEventsEventOne[$key])) {
            $this->domainEventsEventOne[$key] = $this->mockEvent();
        }

        return $this->domainEventsEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDomainEventsEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->domainEventsEventTwo[$key])) {
            $this->domainEventsEventTwo[$key] = $this->mockEvent();
        }

        return $this->domainEventsEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDomainEventsEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->domainEventsEventThree[$key])) {
            $this->domainEventsEventThree[$key] = $this->mockEvent();
        }

        return $this->domainEventsEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return AggregateHistory
     */
    public function getAggregateHistory(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistory[$key])) {
            $this->aggregateHistory[$key] = new AggregateHistory(
                $id,
                [
                    $this->getAggregateHistoryEventOne($id),
                    $this->getAggregateHistoryEventTwo($id),
                    $this->getAggregateHistoryEventThree($id)
                ]
            );
        }

        return $this->aggregateHistory[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistoryEventOne[$key])) {
            $this->aggregateHistoryEventOne[$key] = $this->mockEvent();
        }

        return $this->aggregateHistoryEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistoryEventTwo[$key])) {
            $this->aggregateHistoryEventTwo[$key] = $this->mockEvent();
        }

        return $this->aggregateHistoryEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistoryEventThree[$key])) {
            $this->aggregateHistoryEventThree[$key] = $this->mockEvent();
        }

        return $this->aggregateHistoryEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventStream
     */
    public function getEventStream(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStream[$key])) {
            $this->eventStream[$key] = new EventStream(
                $id,
                [
                    $this->getEventStreamEnvelopeOne($id),
                    $this->getEventStreamEnvelopeTwo($id),
                    $this->getEventStreamEnvelopeThree($id)
                ]
            );
        }

        return $this->eventStream[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEventStreamEnvelopeOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEnvelopeOne[$key])) {
            $this->eventStreamEnvelopeOne[$key] = new EventEnvelope(
                EventId::fromString('event-1'),
                'event_1',
                $this->getEventStreamEventOne($id),
                $id,
                2,
                Timestamp::now(),
                new Metadata([])
            );
        }

        return $this->eventStreamEnvelopeOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEventStreamEnvelopeTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEnvelopeTwo[$key])) {
            $this->eventStreamEnvelopeTwo[$key] = new EventEnvelope(
                EventId::fromString('event-2'),
                'event_2',
                $this->getEventStreamEventTwo($id),
                $id,
                2,
                Timestamp::now(),
                new Metadata([])
            );
        }

        return $this->eventStreamEnvelopeTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEventStreamEnvelopeThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEnvelopeThree[$key])) {
            $this->eventStreamEnvelopeThree[$key] = new EventEnvelope(
                EventId::fromString('event-3'),
                'event_3',
                $this->getEventStreamEventThree($id),
                $id,
                2,
                Timestamp::now(),
                new Metadata([])
            );
        }

        return $this->eventStreamEnvelopeThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventOne[$key])) {
            $this->eventStreamEventOne[$key] = $this->mockEvent();
        }

        return $this->eventStreamEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventTwo[$key])) {
            $this->eventStreamEventTwo[$key] = $this->mockEvent();
        }

        return $this->eventStreamEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventThree[$key])) {
            $this->eventStreamEventThree[$key] = $this->mockEvent();
        }

        return $this->eventStreamEventThree[$key];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEvent()
    {
        $class = 'SimpleES\EventSourcing\Event\DomainEvent';

        $event = $this->testCase->getMock($class);

        return $event;
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockAggregate(IdentifiesAggregate $id)
    {
        $class = 'SimpleES\EventSourcing\Aggregate\TracksEvents';

        $aggregate = $this->testCase->getMock($class);
        $aggregate
            ->expects($this->testCase->any())
            ->method('aggregateId')
            ->will($this->testCase->returnValue($id));

        return $aggregate;
    }
}
