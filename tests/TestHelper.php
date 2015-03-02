<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Collection\EventEnvelopeStream;
use SimpleES\EventSourcing\Collection\EventStream;
use SimpleES\EventSourcing\Event\EventEnvelope;

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

    private $eventStream;
    private $eventStreamEventOne;
    private $eventStreamEventTwo;
    private $eventStreamEventThree;

    private $aggregateHistory;
    private $aggregateHistoryEventOne;
    private $aggregateHistoryEventTwo;
    private $aggregateHistoryEventThree;

    private $envelopeStream;
    private $envelopeStreamEnvelopeOne;
    private $envelopeStreamEnvelopeTwo;
    private $envelopeStreamEnvelopeThree;
    private $envelopeStreamEventOne;
    private $envelopeStreamEventTwo;
    private $envelopeStreamEventThree;

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(\PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function tearDown()
    {
        $this->eventStream           = null;
        $this->eventStreamEventOne   = null;
        $this->eventStreamEventTwo   = null;
        $this->eventStreamEventThree = null;

        $this->aggregateHistory           = null;
        $this->aggregateHistoryEventOne   = null;
        $this->aggregateHistoryEventTwo   = null;
        $this->aggregateHistoryEventThree = null;

        $this->envelopeStream              = null;
        $this->envelopeStreamEnvelopeOne   = null;
        $this->envelopeStreamEnvelopeTwo   = null;
        $this->envelopeStreamEnvelopeThree = null;
        $this->envelopeStreamEventOne      = null;
        $this->envelopeStreamEventTwo      = null;
        $this->envelopeStreamEventThree    = null;
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
                [
                    $this->getEventStreamEventOne($id),
                    $this->getEventStreamEventTwo($id),
                    $this->getEventStreamEventThree($id)
                ]
            );
        }

        return $this->eventStream[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventOne[$key])) {
            $this->eventStreamEventOne[$key] = $this->mockEvent($id);
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
            $this->eventStreamEventTwo[$key] = $this->mockEvent($id);
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
            $this->eventStreamEventThree[$key] = $this->mockEvent($id);
        }

        return $this->eventStreamEventThree[$key];
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
            $this->aggregateHistoryEventOne[$key] = $this->mockEvent($id);
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
            $this->aggregateHistoryEventTwo[$key] = $this->mockEvent($id);
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
            $this->aggregateHistoryEventThree[$key] = $this->mockEvent($id);
        }

        return $this->aggregateHistoryEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelopeStream
     */
    public function getEnvelopeStream(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStream[$key])) {
            $this->envelopeStream[$key] = new EventEnvelopeStream(
                [
                    $this->getEnvelopeStreamEnvelopeOne($id),
                    $this->getEnvelopeStreamEnvelopeTwo($id),
                    $this->getEnvelopeStreamEnvelopeThree($id)
                ]
            );
        }

        return $this->envelopeStream[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEnvelopeStreamEnvelopeOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEnvelopeOne[$key])) {
            $this->envelopeStreamEnvelopeOne[$key] = EventEnvelope::wrap(
                $this->getEnvelopeStreamEventOne($id),
                0
            );
        }

        return $this->envelopeStreamEnvelopeOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEnvelopeStreamEnvelopeTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEnvelopeTwo[$key])) {
            $this->envelopeStreamEnvelopeTwo[$key] = EventEnvelope::wrap(
                $this->getEnvelopeStreamEventTwo($id),
                1
            );
        }

        return $this->envelopeStreamEnvelopeTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEnvelopeStreamEnvelopeThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEnvelopeThree[$key])) {
            $this->envelopeStreamEnvelopeThree[$key] = EventEnvelope::wrap(
                $this->getEnvelopeStreamEventThree($id),
                2
            );
        }

        return $this->envelopeStreamEnvelopeThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEnvelopeStreamEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEventOne[$key])) {
            $this->envelopeStreamEventOne[$key] = $this->mockEvent($id);
        }

        return $this->envelopeStreamEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEnvelopeStreamEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEventTwo[$key])) {
            $this->envelopeStreamEventTwo[$key] = $this->mockEvent($id);
        }

        return $this->envelopeStreamEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEnvelopeStreamEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEventThree[$key])) {
            $this->envelopeStreamEventThree[$key] = $this->mockEvent($id);
        }

        return $this->envelopeStreamEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEvent(IdentifiesAggregate $id)
    {
        $class = 'SimpleES\EventSourcing\Event\SerializableEvent';

        $event = $this->testCase->getMock($class);
        $event
            ->expects($this->testCase->any())
            ->method('aggregateId')
            ->will($this->testCase->returnValue($id));

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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEventStoreMiddlewareForCommit()
    {
        $class = 'SimpleES\EventSourcing\EventStore\Middleware\EventStoreMiddleware';

        $middleware = $this->testCase->getMock($class);
        $middleware
            ->expects($this->testCase->once())
            ->method('commit')
            ->will(
                $this->testCase->returnCallback(
                    function (EventEnvelopeStream $envelopeStream, callable $next) {
                        $next($envelopeStream);
                    }
                )
            );

        return $middleware;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEventStoreMiddlewareForGet()
    {
        $class = 'SimpleES\EventSourcing\EventStore\Middleware\EventStoreMiddleware';

        $middleware = $this->testCase->getMock($class);
        $middleware
            ->expects($this->testCase->once())
            ->method('get')
            ->will(
                $this->testCase->returnCallback(
                    function (IdentifiesAggregate $aggregateId, callable $next) {
                        return $next($aggregateId);
                    }
                )
            );

        return $middleware;
    }
}
