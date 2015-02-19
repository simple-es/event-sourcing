<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Collection\AggregateHistory;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class AggregateHistoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var AggregateHistory
     */
    private $aggregateHistory;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $id = BasketId::fromString('some-id');

        $this->aggregateHistory = new AggregateHistory(
            $id,
            [
                $this->testHelper->getAggregateHistoryEventOne($id),
                $this->testHelper->getAggregateHistoryEventTwo($id),
                $this->testHelper->getAggregateHistoryEventThree($id)
            ]
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $id = BasketId::fromString('some-id');

        $exposedId = $this->aggregateHistory->aggregateId();

        $this->assertTrue($id->equals($exposedId));
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidItemInCollection
     */
    public function itContainsOnlyEventEnvelopes()
    {
        $id = BasketId::fromString('some-id');

        new AggregateHistory(
            $id,
            [new \stdClass()]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\AggregateHistoryIsCorrupt
     */
    public function itContainsOnlyEventEnvelopesWithTheSameAggregateIdAsItself()
    {
        $id = BasketId::fromString('some-id');

        $event = $this->testHelper->mockEvent(BasketId::fromString('other-id'));

        new AggregateHistory(
            $id,
            [$event]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itCannotBeEmpty()
    {
        $id = BasketId::fromString('some-id');

        new AggregateHistory(
            $id,
            []
        );
    }

    /**
     * @test
     */
    public function itExposesWhetherAKeyExistsOrNot()
    {
        $this->assertTrue(isset($this->aggregateHistory[0]));
        $this->assertTrue(isset($this->aggregateHistory[1]));
        $this->assertTrue(isset($this->aggregateHistory[2]));

        $this->assertFalse(isset($this->aggregateHistory[3]));
    }

    /**
     * @test
     */
    public function itExposesItemsByKey()
    {
        $id = BasketId::fromString('some-id');

        $eventOne   = $this->testHelper->getAggregateHistoryEventOne($id);
        $eventTwo   = $this->testHelper->getAggregateHistoryEventTwo($id);
        $eventThree = $this->testHelper->getAggregateHistoryEventThree($id);

        $this->assertSame($eventOne, $this->aggregateHistory[0]);
        $this->assertSame($eventTwo, $this->aggregateHistory[1]);
        $this->assertSame($eventThree, $this->aggregateHistory[2]);

        $this->assertNull($this->aggregateHistory[3]);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itemsCannotBeReplaced()
    {
        $id    = BasketId::fromString('some-id');
        $event = $this->testHelper->getAggregateHistoryEventOne($id);

        $this->aggregateHistory[0] = $event;
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itemsCannotBeRemoved()
    {
        unset($this->aggregateHistory[0]);
    }

    /**
     * @test
     */
    public function itCanBeCounted()
    {
        $this->assertCount(3, $this->aggregateHistory);
    }

    /**
     * @test
     */
    public function itCanBeIteratedOver()
    {
        foreach ($this->aggregateHistory as $event) {
            $this->assertInstanceOf('SimpleES\EventSourcing\Event\SerializableEvent', $event);
        }
    }

    /**
     * @test
     */
    public function itCanBeIterateOverWithIndexes()
    {
        foreach ($this->aggregateHistory as $index => $event) {
            $this->assertInternalType('int', $index);
        }
    }
}
