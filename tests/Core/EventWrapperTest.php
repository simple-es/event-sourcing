<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

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

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->eventWrapper = new EventWrapper();
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itConvertsAnEventStreamToAnEventEnvelopeStream()
    {
        $id          = BasketId::fromString('some-id');
        $eventStream = $this->testHelper->getEventStream($id);

        $envelopeStream = $this->eventWrapper->wrap($id, $eventStream);

        $this->assertInstanceOf('SimpleES\EventSourcing\Collection\EventEnvelopeStream', $envelopeStream);
        $this->assertCount(3, $envelopeStream);
    }

    /**
     * @test
     */
    public function itConvertsAnEventEnvelopeStreamToAnAggregateHistory()
    {
        $id             = BasketId::fromString('some-id');
        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $aggregateHistory = $this->eventWrapper->unwrap($id, $envelopeStream);

        $this->assertInstanceOf('SimpleES\EventSourcing\Collection\AggregateHistory', $aggregateHistory);
        $this->assertCount(3, $aggregateHistory);
    }

    /**
     * @test
     */
    public function itMaintainsConsecutivePlayhead()
    {
        $id          = BasketId::fromString('some-id');
        $eventStream = $this->testHelper->getEventStream($id);

        $envelopeStream = $this->eventWrapper->wrap($id, $eventStream);

        $this->assertSame(0, $envelopeStream[0]->playhead());
        $this->assertSame(1, $envelopeStream[1]->playhead());
        $this->assertSame(2, $envelopeStream[2]->playhead());
    }

    /**
     * @test
     */
    public function itMaintainsConsecutivePlayheadAfterUnwrapping()
    {
        $id             = BasketId::fromString('some-id');
        $eventStream    = $this->testHelper->getEventStream($id);
        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $this->eventWrapper->unwrap($id, $envelopeStream);

        $newEnvelopeStream = $this->eventWrapper->wrap($id, $eventStream);

        $this->assertSame(3, $newEnvelopeStream[0]->playhead());
        $this->assertSame(4, $newEnvelopeStream[1]->playhead());
        $this->assertSame(5, $newEnvelopeStream[2]->playhead());
    }
}
