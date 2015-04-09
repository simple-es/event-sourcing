<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\DomainEvents;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class DomainEventsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var DomainEvents
     */
    private $domainEvents;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $id = BasketId::fromString('some-id');

        $this->domainEvents = new DomainEvents(
            [
                $this->testHelper->getDomainEventsEventOne($id),
                $this->testHelper->getDomainEventsEventTwo($id),
                $this->testHelper->getDomainEventsEventThree($id)
            ]
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper   = null;
        $this->domainEvents = null;
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidTypeInCollection
     */
    public function itContainsOnlyDomainEvents()
    {
        new DomainEvents(
            [new \stdClass()]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itCannotBeEmpty()
    {
        new DomainEvents(
            []
        );
    }

    /**
     * @test
     */
    public function itCanBeIteratedOver()
    {
        $iteratedOverEvents = 0;

        foreach ($this->domainEvents as $event) {
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
        $this->assertCount(3, $this->domainEvents);
    }
}
