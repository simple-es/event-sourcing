<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\EventStore\InMemoryEventStore;
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

        $idOne             = BasketId::fromString('id-1');
        $envelopeStreamOne = $this->testHelper->getEnvelopeStream($idOne);

        $idTwo             = BasketId::fromString('id-2');
        $envelopeStreamTwo = $this->testHelper->getEnvelopeStream($idTwo);

        $this->eventStore = new InMemoryEventStore();

        $this->eventStore->commit($envelopeStreamOne);
        $this->eventStore->commit($envelopeStreamTwo);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itGetsEventsOfASingleAggregate()
    {
        $id = BasketId::fromString('id-1');

        $envelopeStream = $this->eventStore->get($id);

        $this->assertInstanceOf('SimpleES\EventSourcing\Collection\EventEnvelopeStream', $envelopeStream);
        $this->assertCount(3, $envelopeStream);

        $eventEnvelopeOne   = $this->testHelper->getEnvelopeStreamEnvelopeOne($id);
        $eventEnvelopeTwo   = $this->testHelper->getEnvelopeStreamEnvelopeTwo($id);
        $eventEnvelopeThree = $this->testHelper->getEnvelopeStreamEnvelopeThree($id);

        $this->assertSame($eventEnvelopeOne, $envelopeStream[0]);
        $this->assertSame($eventEnvelopeTwo, $envelopeStream[1]);
        $this->assertSame($eventEnvelopeThree, $envelopeStream[2]);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\AggregateIdNotFound
     */
    public function itFailsWhenAnAggregateIdIsNotFound()
    {
        $id = BasketId::fromString('id-3');

        $this->eventStore->get($id);
    }
}
