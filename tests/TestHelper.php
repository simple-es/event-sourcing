<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Identifier\Identifies;
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
     * @param Identifies $id
     * @return DomainEvents
     */
    public function getDomainEvents(Identifies $id)
    {
        $key = $id->toString();

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
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDomainEventsEventOne(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->domainEventsEventOne[$key])) {
            $this->domainEventsEventOne[$key] = $this->mockEvent();
        }

        return $this->domainEventsEventOne[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDomainEventsEventTwo(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->domainEventsEventTwo[$key])) {
            $this->domainEventsEventTwo[$key] = $this->mockEvent();
        }

        return $this->domainEventsEventTwo[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDomainEventsEventThree(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->domainEventsEventThree[$key])) {
            $this->domainEventsEventThree[$key] = $this->mockEvent();
        }

        return $this->domainEventsEventThree[$key];
    }

    /**
     * @param Identifies $id
     * @return AggregateHistory
     */
    public function getAggregateHistory(Identifies $id)
    {
        $key = $id->toString();

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
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventOne(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->aggregateHistoryEventOne[$key])) {
            $this->aggregateHistoryEventOne[$key] = $this->mockEvent();
        }

        return $this->aggregateHistoryEventOne[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventTwo(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->aggregateHistoryEventTwo[$key])) {
            $this->aggregateHistoryEventTwo[$key] = $this->mockEvent();
        }

        return $this->aggregateHistoryEventTwo[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventThree(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->aggregateHistoryEventThree[$key])) {
            $this->aggregateHistoryEventThree[$key] = $this->mockEvent();
        }

        return $this->aggregateHistoryEventThree[$key];
    }

    /**
     * @param Identifies $id
     * @return EventStream
     */
    public function getEventStream(Identifies $id)
    {
        $key = $id->toString();

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
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEnvelopeOne(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->eventStreamEnvelopeOne[$key])) {
            $this->eventStreamEnvelopeOne[$key] = $this->mockEnvelope($id, 0);
        }

        return $this->eventStreamEnvelopeOne[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEnvelopeTwo(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->eventStreamEnvelopeTwo[$key])) {
            $this->eventStreamEnvelopeTwo[$key] = $this->mockEnvelope($id, 1);
        }

        return $this->eventStreamEnvelopeTwo[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEnvelopeThree(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->eventStreamEnvelopeThree[$key])) {
            $this->eventStreamEnvelopeThree[$key] = $this->mockEnvelope($id, 2);
        }

        return $this->eventStreamEnvelopeThree[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventOne(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->eventStreamEventOne[$key])) {
            $this->eventStreamEventOne[$key] = $this->mockEvent();
        }

        return $this->eventStreamEventOne[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventTwo(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->eventStreamEventTwo[$key])) {
            $this->eventStreamEventTwo[$key] = $this->mockEvent();
        }

        return $this->eventStreamEventTwo[$key];
    }

    /**
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventThree(Identifies $id)
    {
        $key = $id->toString();

        if (!isset($this->eventStreamEventThree[$key])) {
            $this->eventStreamEventThree[$key] = $this->mockEvent();
        }

        return $this->eventStreamEventThree[$key];
    }

    /**
     * @param Identifies    $id
     * @param int           $version
     * @param Metadata|null $metadata
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEnvelope(Identifies $id, $version, Metadata $metadata = null)
    {
        $class = 'SimpleES\EventSourcing\Event\Stream\EnvelopsEvent';

        $envelope = $this->testCase->getMock($class);

        $envelope
            ->expects($this->testCase->any())
            ->method('eventId')
            ->will($this->testCase->returnValue(EventId::fromString('event-' . ($version + 1))));

        $envelope
            ->expects($this->testCase->any())
            ->method('eventName')
            ->will($this->testCase->returnValue('event_' . ($version + 1)));

        $envelope
            ->expects($this->testCase->any())
            ->method('event')
            ->will($this->testCase->returnValue($this->getEventStreamEventOne($id)));

        $envelope
            ->expects($this->testCase->any())
            ->method('aggregateId')
            ->will($this->testCase->returnValue($id));

        $envelope
            ->expects($this->testCase->any())
            ->method('aggregateVersion')
            ->will($this->testCase->returnValue($version));

        $envelope
            ->expects($this->testCase->any())
            ->method('tookPlaceAt')
            ->will($this->testCase->returnValue(Timestamp::now()));

        if ($metadata === null) {
            $metadata = new Metadata([]);
        }

        $envelope
            ->expects($this->testCase->any())
            ->method('metadata')
            ->will($this->testCase->returnValue($metadata));

        $envelope
            ->expects($this->testCase->any())
            ->method('enrichMetadata')
            ->will(
                $this->testCase->returnCallback(
                    function (Metadata $newMetadata) use ($id, $version, $metadata) {
                        return $this->mockEnvelope($id, $version, $metadata->merge($newMetadata));
                    }
                )
            );

        return $envelope;
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
     * @param Identifies $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockAggregate(Identifies $id)
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
