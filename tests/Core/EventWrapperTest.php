<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\Stream\EnvelopsEvent;
use SimpleES\EventSourcing\Event\Wrapper\EventWrapper;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EventWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var EventWrapper
     */
    private $eventWrapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $identifierGenerator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventNameResolver;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->identifierGenerator = $this->getMock('SimpleES\EventSourcing\Identifier\GeneratesIdentifiers');

        $this->eventNameResolver = $this->getMock('SimpleES\EventSourcing\Event\NameResolver\ResolvesEventNames');

        $this->eventWrapper = new EventWrapper(
            $this->identifierGenerator,
            $this->eventNameResolver,
            'SimpleES\EventSourcing\Event\Stream\EventEnvelope'
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper          = null;
        $this->eventWrapper        = null;
        $this->identifierGenerator = null;
        $this->eventNameResolver   = null;
    }

    /**
     * @test
     */
    public function itWrapsDomainEventsInAnEventStream()
    {
        $id           = BasketId::fromString('some-id');
        $domainEvents = $this->testHelper->getDomainEvents($id);

        $this->identifierGenerator
            ->expects($this->exactly(3))
            ->method('generateIdentifier');

        $this->eventNameResolver
            ->expects($this->exactly(3))
            ->method('resolveEventName')
            ->with($this->isInstanceOf('SimpleES\EventSourcing\Event\DomainEvent'));

        $envelopeStream = $this->eventWrapper->wrap($id, $domainEvents);

        $this->assertInstanceOf('SimpleES\EventSourcing\Event\Stream\EventStream', $envelopeStream);
        $this->assertCount(3, $envelopeStream);
    }

    /**
     * @test
     */
    public function itUnwrapsAnEventStreamRevealingAggregateHistory()
    {
        $id             = BasketId::fromString('some-id');
        $envelopeStream = $this->testHelper->getEventStream($id);

        $aggregateHistory = $this->eventWrapper->unwrap($envelopeStream);

        $this->assertInstanceOf('SimpleES\EventSourcing\Event\AggregateHistory', $aggregateHistory);
        $this->assertCount(3, $aggregateHistory);
    }

    /**
     * @test
     */
    public function itMaintainsConsecutiveAggregateVersions()
    {
        $id           = BasketId::fromString('some-id');
        $domainEvents = $this->testHelper->getDomainEvents($id);

        $eventStream = $this->eventWrapper->wrap($id, $domainEvents);

        /** @var EnvelopsEvent[] $envelopes */
        $envelopes = iterator_to_array($eventStream);

        $this->assertSame(0, $envelopes[0]->aggregateVersion());
        $this->assertSame(1, $envelopes[1]->aggregateVersion());
        $this->assertSame(2, $envelopes[2]->aggregateVersion());
    }

    /**
     * @test
     */
    public function itMaintainsConsecutivePlayheadAfterUnwrapping()
    {
        $id           = BasketId::fromString('some-id');
        $domainEvents = $this->testHelper->getDomainEvents($id);
        $eventStream  = $this->testHelper->getEventStream($id);

        $this->eventWrapper->unwrap($eventStream);

        $newEnvelopeStream = $this->eventWrapper->wrap($id, $domainEvents);

        /** @var EnvelopsEvent[] $envelopes */
        $envelopes = iterator_to_array($newEnvelopeStream);

        $this->assertSame(3, $envelopes[0]->aggregateVersion());
        $this->assertSame(4, $envelopes[1]->aggregateVersion());
        $this->assertSame(5, $envelopes[2]->aggregateVersion());
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidType
     */
    public function theEventEnvelopeClassMustImplementEnvelopsEvent()
    {
        $this->eventWrapper = new EventWrapper(
            $this->identifierGenerator,
            $this->eventNameResolver,
            'stdClass'
        );
    }
}
