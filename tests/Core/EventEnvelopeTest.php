<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\EventEnvelope;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Test\TestHelper;
use SimpleES\EventSourcing\Timestamp\Timestamp;

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
    private $eventEnvelope;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $event;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $metadata;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tookPlaceAt;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->event       = $this->testHelper->mockEvent(BasketId::fromString('some-id'));
        $this->metadata    = new Metadata(['some-key' => 'Some value']);
        $this->tookPlaceAt = Timestamp::now();

        $this->eventEnvelope = new EventEnvelope(
            $this->event,
            0,
            $this->metadata,
            $this->tookPlaceAt
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itWrapsAnEvent()
    {
        $eventEnvelope = EventEnvelope::wrap($this->event, 0);

        $this->assertInstanceOf('SimpleES\EventSourcing\Event\EventEnvelope', $eventEnvelope);
    }

    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $this->event
            ->expects($this->once())
            ->method('aggregateId');

        $this->eventEnvelope->aggregateId();
    }

    /**
     * @test
     */
    public function itExposesAPlayhead()
    {
        $exposedPlayhead = $this->eventEnvelope->playhead();

        $this->assertSame(0, $exposedPlayhead);
    }

    /**
     * @test
     */
    public function itExposesAnEvent()
    {
        $exposedEvent = $this->eventEnvelope->event();

        $this->assertSame($this->event, $exposedEvent);
    }

    /**
     * @test
     */
    public function itExposesMetadata()
    {
        $exposedMetadata = $this->eventEnvelope->metadata();

        $this->assertSame($this->metadata, $exposedMetadata);
    }

    /**
     * @test
     */
    public function itEnrichesMetadata()
    {
        $metadata = new Metadata(['other-key' => 'Other value']);

        $enrichedEventEnvelope = $this->eventEnvelope->enrichMetadata($metadata);
        $enrichedMetadata      = $enrichedEventEnvelope->metadata();

        $this->assertSame('Some value', $enrichedMetadata['some-key']);
        $this->assertSame('Other value', $enrichedMetadata['other-key']);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenMetadataIsenrich()
    {
        $metadata = new Metadata(['other-key' => 'Other value']);

        $this->eventEnvelope->enrichMetadata($metadata);

        $this->assertSame($this->metadata, $this->eventEnvelope->metadata());
    }

    /**
     * @test
     */
    public function itExposesWhenItTookPlace()
    {
        $exposedTookPlaceAt = $this->eventEnvelope->tookPlaceAt();

        $this->assertSame($this->tookPlaceAt, $exposedTookPlaceAt);
    }
}
