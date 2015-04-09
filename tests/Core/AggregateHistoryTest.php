<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\AggregateHistory;
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

        $this->testHelper       = null;
        $this->aggregateHistory = null;
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
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidTypeInCollection
     */
    public function itContainsOnlyEvents()
    {
        $id = BasketId::fromString('some-id');

        new AggregateHistory(
            $id,
            [new \stdClass()]
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
    public function itCanBeIteratedOver()
    {
        $iteratedOverEvents = 0;

        foreach ($this->aggregateHistory as $event) {
            $this->assertInstanceOf('SimpleES\EventSourcing\Event\DomainEvent', $event);
            $iteratedOverEvents++;
        }

        $this->assertSame(3, $iteratedOverEvents);
    }

    /**
     * @test
     */
    public function itCanBeCounted()
    {
        $this->assertCount(3, $this->aggregateHistory);
    }
}
