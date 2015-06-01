<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Event\Store\Decorator\MetadataEnrichingDecorator;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class MetadataEnrichingDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var MetadataEnrichingDecorator
     */
    private $eventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $nextEventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $metadataEnricher;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->nextEventStore = $this->getMock('SimpleES\EventSourcing\Event\Store\StoresEvents');

        $this->metadataEnricher = $this->getMock('SimpleES\EventSourcing\Metadata\EnrichesMetadata');

        $this->eventStore = new MetadataEnrichingDecorator([$this->metadataEnricher], $this->nextEventStore);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();

        $this->testHelper     = null;
        $this->eventStore     = null;
        $this->nextEventStore = null;
    }

    /**
     * @test
     */
    public function itEnrichesMetadataWhenEventsAreCommittedBeforePassingThemToTheNextEventStore()
    {
        $id = BasketId::fromString('some-id');

        $eventStream = $this->testHelper->getEventStream($id);

        $envelopeOne   = $this->testHelper->getEventStreamEnvelopeOne($id);
        $envelopeTwo   = $this->testHelper->getEventStreamEnvelopeTwo($id);
        $envelopeThree = $this->testHelper->getEventStreamEnvelopeThree($id);

        $this->metadataEnricher
            ->expects($this->exactly(3))
            ->method('enrich')
            ->withConsecutive(
                [$this->equalTo($envelopeOne)],
                [$this->equalTo($envelopeTwo)],
                [$this->equalTo($envelopeThree)]
            )
            ->will($this->returnArgument(0));

        $this->nextEventStore
            ->expects($this->once())
            ->method('commit')
            ->with($this->isInstanceOf('SimpleES\EventSourcing\Event\EventStream'));

        $this->eventStore->commit($eventStream);
    }

    /**
     * @test
     */
    public function itSimplyProxiesGettingEventsToTheNextEventStore()
    {
        $id = BasketId::fromString('some-id');

        $eventStream = $this->testHelper->getEventStream($id);

        $this->nextEventStore
            ->expects($this->once())
            ->method('read')
            ->with($this->equalTo($id))
            ->will($this->returnValue($eventStream));

        $returnedEnvelopeStream = $this->eventStore->read($id);

        $this->assertSame($eventStream, $returnedEnvelopeStream);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidTypeInCollection
     */
    public function itsListOfMetadataEnrichersMustBeOfTheCorrectType()
    {
        new MetadataEnrichingDecorator([new \stdClass()], $this->nextEventStore);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itsListOfMetadataEnrichersCannotBeEmpty()
    {
        new MetadataEnrichingDecorator([], $this->nextEventStore);
    }
}
