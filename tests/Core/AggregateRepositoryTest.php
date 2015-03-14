<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Aggregate\Repository\AggregateRepository;
use SimpleES\EventSourcing\Example\Basket\Basket;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
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
    private $eventWrapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregateFactory;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->eventWrapper = $this->getMock('SimpleES\EventSourcing\Event\Wrapper\WrapsEvents');

        $this->eventStore = $this->getMock('SimpleES\EventSourcing\Event\Store\StoresEvents');

        $this->aggregateFactory = $this->getMock('SimpleES\EventSourcing\Aggregate\Factory\ReconstitutesAggregates');

        $this->repository = new AggregateRepository(
            $this->eventWrapper,
            $this->eventStore,
            $this->aggregateFactory
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper       = null;
        $this->repository       = null;
        $this->eventWrapper     = null;
        $this->eventStore       = null;
        $this->aggregateFactory = null;
    }

    /**
     * @test
     */
    public function itSavesAnAggregate()
    {
        $id = BasketId::fromString('some-basket');

        $domainEvents = $this->testHelper->getDomainEvents($id);
        $eventStream  = $this->testHelper->getEventStream($id);

        $aggregate = $this->testHelper->mockAggregate($id);
        $aggregate
            ->expects($this->once())
            ->method('recordedEvents')
            ->will($this->returnValue($domainEvents));
        $aggregate
            ->expects($this->once())
            ->method('clearRecordedEvents');

        $this->eventWrapper
            ->expects($this->once())
            ->method('wrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($domainEvents)
            )
            ->will($this->returnValue($eventStream));

        $this->eventStore
            ->expects($this->once())
            ->method('commit')
            ->with($this->equalTo($eventStream));

        $this->repository->save($aggregate);
    }

    /**
     * @test
     */
    public function itFetchesAnAggregate()
    {
        $id = BasketId::fromString('some-basket');

        $eventStream      = $this->testHelper->getEventStream($id);
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = Basket::pickUp($id);

        $this->eventStore
            ->expects($this->once())
            ->method('read')
            ->with($this->equalTo($id))
            ->will($this->returnValue($eventStream));

        $this->eventWrapper
            ->expects($this->once())
            ->method('unwrap')
            ->with($this->equalTo($eventStream))
            ->will($this->returnValue($aggregateHistory));

        $this->aggregateFactory
            ->expects($this->once())
            ->method('reconstituteFromHistory')
            ->with($this->equalTo($aggregateHistory))
            ->will($this->returnValue($aggregate));

        $foundAggregate = $this->repository->fetch($id);

        $this->assertSame($aggregate, $foundAggregate);
    }
}
