<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
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
     * @var IdentifiesAggregate
     */
    private $id;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregate;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->id        = BasketId::fromString('some-id');
        $this->aggregate = $this->testHelper->mockAggregate($this->id);

        $this->identityMap = new IdentityMap();

        $this->identityMap->add($this->aggregate);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itHoldsAnAggregate()
    {
        $retrievedAggregate = $this->identityMap->get($this->id);

        $this->assertSame($this->aggregate, $retrievedAggregate);
    }

    /**
     * @test
     */
    public function itKnowsItHoldsAnAggregate()
    {
        $hasAggregate = $this->identityMap->contains($this->id);

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
        $this->identityMap->clear();

        $this->identityMap->get($this->id);
    }
}
