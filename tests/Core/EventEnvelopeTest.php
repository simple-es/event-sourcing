<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\Stream\EventEnvelope;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EventEnvelopeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var EventEnvelope
     */
    private $envelope;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $event;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $aggregateId = BasketId::fromString('some-id');
        $eventId     = EventId::fromString('some-id');

        $this->event = $this->testHelper->mockEvent();

        $this->envelope = EventEnvelope::envelop(
            $eventId,
            'some.name',
            $this->event,
            $aggregateId,
            123
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper = null;
        $this->envelope   = null;
        $this->event      = null;
    }

    /**
     * @test
     */
    public function itExposesAnEventId()
    {
        $id = EventId::fromString('some-id');

        $exposedId = $this->envelope->eventId();

        $this->assertTrue($id->equals($exposedId));
    }

    /**
     * @test
     */
    public function itExposesAnEventName()
    {
        $exposeName = $this->envelope->eventName();

        $this->assertSame('some.name', $exposeName);
    }

    /**
     * @test
     */
    public function itExposesAnEvent()
    {
        $exposedEvent = $this->envelope->event();

        $this->assertSame($this->event, $exposedEvent);
    }

    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $id = BasketId::fromString('some-id');

        $exposedId = $this->envelope->aggregateId();

        $this->assertTrue($id->equals($exposedId));
    }

    /**
     * @test
     */
    public function itExposesAnAggregateVersion()
    {
        $exposedVersion = $this->envelope->aggregateVersion();

        $this->assertSame(123, $exposedVersion);
    }

    /**
     * @test
     */
    public function itExposesWhenItTookPlace()
    {
        $exposedTimestamp = $this->envelope->tookPlaceAt();

        $this->assertInstanceOf('SimpleES\EventSourcing\Timestamp\Timestamp', $exposedTimestamp);
    }

    /**
     * @test
     */
    public function itExposesMetadata()
    {
        $exposedMetadata = $this->envelope->metadata();

        $this->assertInstanceOf('SimpleES\EventSourcing\Metadata\Metadata', $exposedMetadata);
    }

    /**
     * @test
     */
    public function itEnrichesMetadata()
    {
        $enrichedEnvelope = $this->envelope->enrichMetadata(new Metadata(['some-key' => 'Some value']));
        $enrichedMetadata = $enrichedEnvelope->metadata();

        $this->assertSame('Some value', $enrichedMetadata['some-key']);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenMetadataIsenrich()
    {
        $originalMetadata = $this->envelope->metadata();

        $this->envelope->enrichMetadata(new Metadata(['some-key' => 'Some value']));

        $this->assertSame($originalMetadata, $this->envelope->metadata());
    }
}
