<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\IdentityMap\IdentityMap;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class IdentityMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregate;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $id = BasketId::fromString('some-id');

        $this->aggregate = $this->testHelper->mockAggregate($id);

        $this->identityMap = new IdentityMap();

        $this->identityMap->add($this->aggregate);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper  = null;
        $this->identityMap = null;
    }

    /**
     * @test
     */
    public function itHoldsAnAggregate()
    {
        $id = BasketId::fromString('some-id');

        $retrievedAggregate = $this->identityMap->get($id);

        $this->assertSame($this->aggregate, $retrievedAggregate);
    }

    /**
     * @test
     */
    public function itKnowsItHoldsAnAggregate()
    {
        $id = BasketId::fromString('some-id');

        $hasAggregate = $this->identityMap->contains($id);

        $this->assertTrue($hasAggregate);
    }

    /**
     * @test
     */
    public function itKnowsItDoesNotHoldAnAggregate()
    {
        $otherId = BasketId::fromString('other-id');

        $hasAggregate = $this->identityMap->contains($otherId);

        $this->assertFalse($hasAggregate);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\DuplicateAggregateFound
     */
    public function itCannotHoldAnAggregateMoreThanOnce()
    {
        $this->identityMap->add($this->aggregate);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\AggregateIdNotFound
     */
    public function itCanBeCleared()
    {
        $id = BasketId::fromString('some-id');

        $this->identityMap->clear();

        $this->identityMap->get($id);
    }
}
