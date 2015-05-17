<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Aggregate\Manager\AggregateManager;
use SimpleES\EventSourcing\Example\Basket\BasketId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class AggregateManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AggregateManager
     */
    private $aggregateManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $identityMap;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    public function setUp()
    {
        $this->identityMap = $this->getMock('SimpleES\EventSourcing\IdentityMap\MapsIdentity');

        $this->repository = $this->getMock('SimpleES\EventSourcing\Aggregate\Repository\Repository');

        $this->aggregateManager = new AggregateManager($this->identityMap, $this->repository);
    }

    public function tearDown()
    {
        $this->aggregateManager = null;
        $this->identityMap      = null;
        $this->repository       = null;
    }

    /**
     * @test
     */
    public function itAddsAnAggregateToTheRepository()
    {
        $id = BasketId::fromString('some-id');

        $aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $aggregate
            ->expects($this->any())
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($id)
            ->will($this->returnValue(false));

        $this->aggregateManager->add($aggregate);
    }

    /**
     * @test
     */
    public function itAlsoAddsTheAggregateToTheIdentityMap()
    {
        $id = BasketId::fromString('some-id');

        $aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $aggregate
            ->expects($this->any())
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($id)
            ->will($this->returnValue(false));

        $this->identityMap
            ->expects($this->once())
            ->method('add')
            ->with($aggregate);

        $this->aggregateManager->add($aggregate);
    }

    /**
     * @test
     */
    public function itDoesNotAddTheAggregateToTheIdentityMapIfItIsAlreadyInThere()
    {
        $id = BasketId::fromString('some-id');

        $aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $aggregate
            ->expects($this->any())
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($id)
            ->will($this->returnValue(true));

        $this->identityMap
            ->expects($this->never())
            ->method('add');

        $this->aggregateManager->add($aggregate);
    }

    /**
     * @test
     */
    public function itGetsAnAggregateFromTheRepository()
    {
        $id = BasketId::fromString('some-id');

        $aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $aggregate
            ->expects($this->any())
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($id)
            ->will($this->returnValue(false));

        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->will($this->returnValue($aggregate));

        $gotAggregate = $this->aggregateManager->get($id);

        $this->assertSame($aggregate, $gotAggregate);
    }

    /**
     * @test
     */
    public function itAddsTheAggregateToTheIdentityMapWhenGettingItFromTheRepository()
    {
        $id = BasketId::fromString('some-id');

        $aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $aggregate
            ->expects($this->any())
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($id)
            ->will($this->returnValue(false));

        $this->identityMap
            ->expects($this->once())
            ->method('add')
            ->with($aggregate);

        $this->repository
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->will($this->returnValue($aggregate));

        $gotAggregate = $this->aggregateManager->get($id);

        $this->assertSame($aggregate, $gotAggregate);
    }

    /**
     * @test
     */
    public function itGetsTheAggregateFromTheIdentityMapIfItIsInThere()
    {
        $id = BasketId::fromString('some-id');

        $aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $aggregate
            ->expects($this->any())
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->identityMap
            ->expects($this->once())
            ->method('contains')
            ->with($id)
            ->will($this->returnValue(true));

        $this->identityMap
            ->expects($this->once())
            ->method('get')
            ->with($id)
            ->will($this->returnValue($aggregate));

        $this->identityMap
            ->expects($this->never())
            ->method('add');

        $this->repository
            ->expects($this->never())
            ->method('get');

        $gotAggregate = $this->aggregateManager->get($id);

        $this->assertSame($aggregate, $gotAggregate);
    }

    /**
     * @test
     */
    public function itClearsTheIdentityMap()
    {
        $this->identityMap
            ->expects($this->once())
            ->method('clear');

        $this->aggregateManager->clear();
    }
}
