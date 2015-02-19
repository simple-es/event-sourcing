<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\UnitOfWork\UnitOfWork;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class UnitOfWorkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var BasketId
     */
    private $id;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregate;

    public function setUp()
    {
        $this->repository = $this->getMock('SimpleES\EventSourcing\Repository\Repository');

        $this->id = BasketId::fromString('some-id');

        $this->aggregate = $this->getMock('SimpleES\EventSourcing\Aggregate\TracksEvents');
        $this->aggregate
            ->method('aggregateId')
            ->will($this->returnValue($this->id));

        $this->unitOfWork = new UnitOfWork($this->repository);
    }

    /**
     * @test
     */
    public function itTracksAnAggregate()
    {
        $this->unitOfWork->track($this->aggregate);

        $this->assertSame($this->aggregate, $this->unitOfWork->find($this->id));
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\DuplicateAggregateFound
     */
    public function itDoesNotTrackTheSameAggregateMoreThanOnce()
    {
        $this->unitOfWork->track($this->aggregate);
        $this->unitOfWork->track($this->aggregate);
    }

    /**
     * @test
     */
    public function itConsultsARepositoryWhenFindingAnAggregateThatIsNotTrackedYet()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->will($this->returnValue($this->aggregate));

        $this->assertSame($this->aggregate, $this->unitOfWork->find($this->id));
    }

    /**
     * @test
     */
    public function itConsultsARepositoryOnlyOnceWhenFindingTheSameAggregateMoreThanOnce()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->will($this->returnValue($this->aggregate));

        $this->unitOfWork->find($this->id);
        $this->unitOfWork->find($this->id);
    }

    /**
     * @test
     */
    public function itCommitsAnyChanges()
    {
        $this->aggregate
            ->method('hasRecordedEvents')
            ->will($this->returnValue(true));

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->aggregate);

        $this->unitOfWork->track($this->aggregate);

        $this->unitOfWork->commit();
    }
}
