<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Store\Decorator;

use SimpleES\EventSourcing\Event\Store\StoresEvents;
use SimpleES\EventSourcing\Event\Stream\EventEnvelope;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Exception\CollectionIsEmpty;
use SimpleES\EventSourcing\Exception\InvalidItemInCollection;
use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Metadata\EnrichesMetadata;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class MetadataEnrichingDecorator implements StoresEvents
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
    public function commit(EventStream $eventStream)
    {
        $enrichedEnvelopes = [];

        /** @var EventEnvelope $envelope */
        foreach ($eventStream as $envelope) {
            /** @var EnrichesMetadata $metadataEnricher */
            foreach ($this->metadataEnrichers as $metadataEnricher) {
                $envelope = $metadataEnricher->enrich($envelope);
            }

            $enrichedEnvelopes[] = $envelope;
        }

        $this->next->commit(new EventStream($eventStream->aggregateId(), $enrichedEnvelopes));
    }

    /**
     * {@inheritdoc}
     */
    public function read(Identifies $aggregateId)
    {
        return $this->next->read($aggregateId);
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
