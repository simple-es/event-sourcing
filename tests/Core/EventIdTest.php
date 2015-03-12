<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Event\Stream\EventId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EventIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventId
     */
    private $eventId;

    public function setUp()
    {
        $this->eventId = EventId::fromString('event-1');
    }

    public function tearDown()
    {
        $this->eventId = null;
    }

    /**
     * @test
     */
    public function itConvertsToAString()
    {
        $this->assertSame('event-1', (string)$this->eventId);
    }

    /**
     * @test
     */
    public function itEqualsAnotherWithTheSameClassAndValue()
    {
        $other = EventId::fromString('event-1');

        $this->assertTrue($this->eventId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentValue()
    {
        $other = EventId::fromString('event-2');

        $this->assertNotTrue($this->eventId->equals($other));
    }
}
