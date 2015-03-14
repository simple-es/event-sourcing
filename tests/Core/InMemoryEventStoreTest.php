<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\Store\InMemoryEventStore;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class InMemoryEventStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var InMemoryEventStore
     */
    private $eventStore;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $idOne          = BasketId::fromString('id-1');
        $eventStreamOne = $this->testHelper->getEventStream($idOne);

        $idTwo          = BasketId::fromString('id-2');
        $eventStreamTwo = $this->testHelper->getEventStream($idTwo);

        $this->eventStore = new InMemoryEventStore();

        $this->eventStore->commit($eventStreamOne);
        $this->eventStore->commit($eventStreamTwo);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper = null;
        $this->eventStore = null;
    }

    /**
     * @test
     */
    public function itGetsEventsOfASingleAggregate()
    {
        $id = BasketId::fromString('id-1');

        $eventStream = $this->eventStore->read($id);
        $envelopes   = iterator_to_array($eventStream);

        $this->assertInstanceOf('SimpleES\EventSourcing\Event\Stream\EventStream', $eventStream);
        $this->assertCount(3, $eventStream);

        $envelopeOne   = $this->testHelper->getEventStreamEnvelopeOne($id);
        $envelopeTwo   = $this->testHelper->getEventStreamEnvelopeTwo($id);
        $envelopeThree = $this->testHelper->getEventStreamEnvelopeThree($id);

        $this->assertSame($envelopeOne, $envelopes[0]);
        $this->assertSame($envelopeTwo, $envelopes[1]);
        $this->assertSame($envelopeThree, $envelopes[2]);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\AggregateIdNotFound
     */
    public function itFailsWhenAnAggregateIdIsNotFound()
    {
        $id = BasketId::fromString('id-3');

        $this->eventStore->read($id);
    }
}
