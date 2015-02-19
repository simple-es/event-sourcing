<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\EventStore\Decorator\MetadataEnrichingDecorator;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
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

        $this->nextEventStore = $this->getMock('SimpleES\EventSourcing\EventStore\StoresEvents');

        $this->metadataEnricher = $this->getMock('SimpleES\EventSourcing\Metadata\EnrichesMetadata');

        $this->eventStore = new MetadataEnrichingDecorator([$this->metadataEnricher], $this->nextEventStore);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itEnrichesMetadataWhenEventsAreCommittedBeforePassingThemToTheNextEventStore()
    {
        $id = BasketId::fromString('some-id');

        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $eventEnvelopeOne   = $this->testHelper->getEnvelopeStreamEnvelopeOne($id);
        $eventEnvelopeTwo   = $this->testHelper->getEnvelopeStreamEnvelopeTwo($id);
        $eventEnvelopeThree = $this->testHelper->getEnvelopeStreamEnvelopeThree($id);

        $this->metadataEnricher
            ->expects($this->exactly(3))
            ->method('enrich')
            ->withConsecutive(
                [$this->equalTo($eventEnvelopeOne)],
                [$this->equalTo($eventEnvelopeTwo)],
                [$this->equalTo($eventEnvelopeThree)]
            )
            ->will($this->returnArgument(0));

        $this->nextEventStore
            ->expects($this->once())
            ->method('commit')
            ->with($this->isInstanceOf('SimpleES\EventSourcing\Collection\EventEnvelopeStream'));

        $this->eventStore->commit($envelopeStream);
    }

    /**
     * @test
     */
    public function itSimplyProxiesGettingEventsToTheNextEventStore()
    {
        $id = BasketId::fromString('some-id');

        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $this->nextEventStore
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($envelopeStream));

        $returnedEnvelopeStream = $this->eventStore->get($id);

        $this->assertSame($envelopeStream, $returnedEnvelopeStream);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\InvalidItemInCollection
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
