<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Aggregate\Factory\ClassMappingFactory;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class ClassMappingFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itReconstituteAnAggregateFromHistory()
    {
        $factory = new ClassMappingFactory(
            ['SimpleES\EventSourcing\Example\Basket\BasketId' => 'SimpleES\EventSourcing\Example\Basket\Basket']
        );

        $id               = BasketId::fromString('some-id');
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = $factory->reconstituteFromHistory($aggregateHistory);

        $this->assertInstanceOf('SimpleES\EventSourcing\Example\Basket\Basket', $aggregate);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\IdNotMappedToAggregate
     */
    public function itCannotReconstituteAnAggregateWhenMapNotFound()
    {
        $factory = new ClassMappingFactory([]);

        $id               = BasketId::fromString('some-id');
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $factory->reconstituteFromHistory($aggregateHistory);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidItemInCollection
     */
    public function theMapKeysMustBeClassNamesImplementingIdentifiesAggregate()
    {
        new ClassMappingFactory(
            ['stdClass' => 'SimpleES\EventSourcing\Example\Basket\Basket']
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidItemInCollection
     */
    public function theMapValuesMustBeClassNamesImplementingTracksEvents()
    {
        new ClassMappingFactory(
            ['SimpleES\EventSourcing\Example\Basket\BasketId' => 'stdClass']
        );
    }
}
