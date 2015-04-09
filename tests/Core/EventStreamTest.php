<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EventStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var EventStream
     */
    private $eventStream;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $id = BasketId::fromString('some-id');

        $this->eventStream = new EventStream(
            $id,
            [
                $this->testHelper->getEventStreamEnvelopeOne($id),
                $this->testHelper->getEventStreamEnvelopeTwo($id),
                $this->testHelper->getEventStreamEnvelopeThree($id)
            ]
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper  = null;
        $this->eventStream = null;
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidTypeInCollection
     */
    public function itContainsOnlyEnvelopes()
    {
        $id = BasketId::fromString('some-id');

        new EventStream(
            $id,
            [new \stdClass()]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\EventStreamIsCorrupt
     */
    public function itContainsOnlyEnvelopesWithTheSameAggregateIdAsItself()
    {
        $id      = BasketId::fromString('some-id');
        $otherId = BasketId::fromString('other-id');

        new EventStream(
            $id,
            [$this->testHelper->getEventStreamEnvelopeOne($otherId)]
        );
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itCannotBeEmpty()
    {
        $id = BasketId::fromString('some-id');

        new EventStream(
            $id,
            []
        );
    }

    /**
     * @test
     */
    public function itCanBeIteratedOver()
    {
        $iteratedOverEnvelopes = 0;

        foreach ($this->eventStream as $envelope) {
            $this->assertInstanceOf('SimpleES\EventSourcing\Event\Stream\EnvelopsEvent', $envelope);
            $iteratedOverEnvelopes++;
        }

        $this->assertSame(3, $iteratedOverEnvelopes);
    }

    /**
     * @test
     */
    public function itCanBeCounted()
    {
        $this->assertCount(3, $this->eventStream);
    }
}
