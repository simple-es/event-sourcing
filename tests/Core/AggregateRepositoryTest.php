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
    private $identityMap;

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

        $this->identityMap = $this->getMock('SimpleES\EventSourcing\IdentityMap\IdentityMap');

        $this->eventWrapper = $this->getMock('SimpleES\EventSourcing\Event\Wrapper\WrapsEvents');

        $this->eventStore = $this->getMock('SimpleES\EventSourcing\Event\Store\StoresEvents');

        $this->aggregateFactory = $this->getMock('SimpleES\EventSourcing\Aggregate\Factory\ReconstitutesAggregates');

        $this->repository = new AggregateRepository(
            $this->identityMap,
            $this->eventWrapper,
            $this->eventStore,
            $this->aggregateFactory
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper   = null;
        $this->repository   = null;
        $this->identityMap  = null;
        $this->eventWrapper = null;
        $this->eventStore   = null;
    }

    /**
     * @test
     */
    public function anAggregateCanBeAddedToIt()
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

        $this->identityMap
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo($aggregate));

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

        $this->repository->add($aggregate);
    }

    /**
     * @test
     */
    public function anAggregateCanBeRetrievedFromIt()
    {
        $id = BasketId::fromString('some-basket');

        $eventStream      = $this->testHelper->getEventStream($id);
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = Basket::pickUp($id);

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($this->equalTo($id))
            ->will($this->returnValue(false));

        $this->eventStore
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($eventStream));

        $this->eventWrapper
            ->expects($this->once())
            ->method('unwrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($eventStream)
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

    /**
     * @test
     */
    public function itExposesAnAggregateDirectlyWhenAllreadyAdded()
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

        $this->identityMap
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo($aggregate));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($this->equalTo($id))
            ->will($this->returnValue(true));

        $this->identityMap
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($aggregate));

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

        $this->repository->add($aggregate);

        $foundAggregate = $this->repository->find($id);

        $this->assertSame($aggregate, $foundAggregate);
    }

    /**
     * @test
     */
    public function itExposesAnAggregateDirectlyWhenRetrievedBefore()
    {
        $id = BasketId::fromString('some-basket');

        $eventStream      = $this->testHelper->getEventStream($id);
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = Basket::pickUp($id);

        $this->identityMap
            ->expects($this->exactly(2))
            ->method('contains')
            ->with($this->equalTo($id))
            ->will(
                $this->onConsecutiveCalls(
                    $this->returnValue(false),
                    $this->returnValue(true)
                )
            );

        $this->identityMap
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($aggregate));

        $this->eventStore
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($eventStream));

        $this->eventWrapper
            ->expects($this->once())
            ->method('unwrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($eventStream)
            )
            ->will($this->returnValue($aggregateHistory));

        $this->aggregateFactory
            ->expects($this->once())
            ->method('reconstituteFromHistory')
            ->with($this->equalTo($aggregateHistory))
            ->will($this->returnValue($aggregate));

        $this->repository->find($id);

        $foundAggregate = $this->repository->find($id);

        $this->assertSame($aggregate, $foundAggregate);
    }
}
