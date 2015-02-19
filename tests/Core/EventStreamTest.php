<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Collection\EventStream;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EventStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var EventStream
     */
    private $eventStream;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $id = BasketId::fromString('some-id');

        $this->eventStream = new EventStream(
            [
                $this->testHelper->getEventStreamEventOne($id),
                $this->testHelper->getEventStreamEventTwo($id),
                $this->testHelper->getEventStreamEventThree($id)
            ]
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidItemInCollection
     */
    public function itContainsOnlyEventEnvelopes()
    {
        new EventStream(
            [new \stdClass()]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itCannotBeEmpty()
    {
        new EventStream(
            []
        );
    }

    /**
     * @test
     */
    public function itExposesWhetherAKeyExistsOrNot()
    {
        $this->assertTrue(isset($this->eventStream[0]));
        $this->assertTrue(isset($this->eventStream[1]));
        $this->assertTrue(isset($this->eventStream[2]));

        $this->assertFalse(isset($this->eventStream[3]));
    }

    /**
     * @test
     */
    public function itExposesItemsByKey()
    {
        $id = BasketId::fromString('some-id');

        $eventOne   = $this->testHelper->getEventStreamEventOne($id);
        $eventTwo   = $this->testHelper->getEventStreamEventTwo($id);
        $eventThree = $this->testHelper->getEventStreamEventThree($id);

        $this->assertSame($eventOne, $this->eventStream[0]);
        $this->assertSame($eventTwo, $this->eventStream[1]);
        $this->assertSame($eventThree, $this->eventStream[2]);

        $this->assertNull($this->eventStream[3]);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itemsCannotBeReplaced()
    {
        $id    = BasketId::fromString('some-id');
        $event = $this->testHelper->getEventStreamEventOne($id);

        $this->eventStream[0] = $event;
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itemsCannotBeRemoved()
    {
        unset($this->eventStream[0]);
    }

    /**
     * @test
     */
    public function itCanBeCounted()
    {
        $this->assertCount(3, $this->eventStream);
    }

    /**
     * @test
     */
    public function itCanBeIteratedOver()
    {
        foreach ($this->eventStream as $event) {
            $this->assertInstanceOf('SimpleES\EventSourcing\Event\SerializableEvent', $event);
        }
    }

    /**
     * @test
     */
    public function itCanBeIterateOverWithIndexes()
    {
        foreach ($this->eventStream as $index => $event) {
            $this->assertInternalType('int', $index);
        }
    }
}
