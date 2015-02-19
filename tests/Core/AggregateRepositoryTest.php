<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Basket\Basket;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Repository\AggregateRepository;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class AggregateRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var AggregateRepository
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventWrapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregateFactory;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->eventWrapper = $this->getMock('SimpleES\EventSourcing\Event\Wrapper\WrapsEvents');

        $this->eventStore = $this->getMock('SimpleES\EventSourcing\EventStore\StoresEvents');

        $this->aggregateFactory = $this->getMock('SimpleES\EventSourcing\Aggregate\Factory\ReconstitutesAggregates');

        $this->repository = new AggregateRepository($this->eventWrapper, $this->eventStore, $this->aggregateFactory);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itAddsAggregates()
    {
        $id = BasketId::fromString('some-basket');

        $eventStream    = $this->testHelper->getEventStream($id);
        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $aggregate = $this->testHelper->mockAggregate($id);
        $aggregate
            ->expects($this->once())
            ->method('recordedEvents')
            ->will($this->returnValue($eventStream));
        $aggregate
            ->expects($this->once())
            ->method('clearRecordedEvents');

        $this->eventWrapper
            ->expects($this->once())
            ->method('wrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($eventStream)
            )
            ->will($this->returnValue($envelopeStream));

        $this->eventStore
            ->expects($this->once())
            ->method('commit')
            ->with($this->equalTo($envelopeStream));

        $this->repository->add($aggregate);
    }

    /**
     * @test
     */
    public function itFindsAggregateIds()
    {
        $id = BasketId::fromString('some-basket');

        $envelopeStream   = $this->testHelper->getEnvelopeStream($id);
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = Basket::pickUp($id);

        $this->eventStore
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($envelopeStream));

        $this->eventWrapper
            ->expects($this->once())
            ->method('unwrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($envelopeStream)
            )
            ->will($this->returnValue($aggregateHistory));

        $this->aggregateFactory
            ->expects($this->once())
            ->method('reconstituteFromHistory')
            ->with($this->equalTo($aggregateHistory))
            ->will($this->returnValue($aggregate));

        $foundAggregate = $this->repository->find($id);

        $this->assertSame($aggregate, $foundAggregate);
    }
}
