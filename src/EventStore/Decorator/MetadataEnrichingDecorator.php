<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\EventStore\Decorator;

use SimpleES\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use SimpleES\EventSourcing\Collection\EventEnvelopeStream;
use SimpleES\EventSourcing\Event\EventEnvelope;
use SimpleES\EventSourcing\EventStore\StoresEvents;
use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\InvalidItemInCollection;
use SimpleES\EventSourcing\Metadata\EnrichesMetadata;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class MetadataEnrichingDecorator implements StoresEvents
{
    /**
     * @var EnrichesMetadata[]
     */
    private $metadataEnrichers;

    /**
     * @var StoresEvents
     */
    private $next;

    /**
     * @param EnrichesMetadata[] $metadataEnrichers
     * @param StoresEvents       $next
     */
    public function __construct(array $metadataEnrichers, StoresEvents $next)
    {
        foreach ($metadataEnrichers as $metadataEnricher) {
            $this->guardMetadataEnricherType($metadataEnricher);

            $this->metadataEnrichers[] = $metadataEnricher;
        }

        $this->guardAmountOfMetadataEnrichers();

        $this->next = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(EventEnvelopeStream $envelopeStream)
    {
        $enrichedEventEnvelopes = [];

        /** @var EventEnvelope $eventEnvelope */
        foreach ($envelopeStream as $eventEnvelope) {
            /** @var EnrichesMetadata $metadataEnricher */
            foreach ($this->metadataEnrichers as $metadataEnricher) {
                $eventEnvelope = $metadataEnricher->enrich($eventEnvelope);
            }

            $enrichedEventEnvelopes[] = $eventEnvelope;
        }

        $this->next->commit(new EventEnvelopeStream($enrichedEventEnvelopes));
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdentifiesAggregate $aggregateId)
    {
        return $this->next->get($aggregateId);
    }

    /**
     * @param mixed $metadataEnricher
     * @throws InvalidItemInCollection
     */
    private function guardMetadataEnricherType($metadataEnricher)
    {
        if (!($metadataEnricher instanceof EnrichesMetadata)) {
            throw InvalidItemInCollection::create(
                $metadataEnricher,
                'SimpleES\EventSourcing\Metadata\EnrichesMetadata'
            );
        }
    }

    /**
     * @throws CollectionIsEmpty
     */
    private function guardAmountOfMetadataEnrichers()
    {
        if (!$this->metadataEnrichers) {
            throw CollectionIsEmpty::create();
        }
    }
}
