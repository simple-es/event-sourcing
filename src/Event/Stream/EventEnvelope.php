<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event\Stream;

use SimpleES\EventSourcing\Event\DomainEvent;
use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Timestamp\Timestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventEnvelope implements DomainEvent
{
    /**
     * @var EventId
     */
    private $eventId;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var DomainEvent
     */
    private $event;

    /**
     * @var Identifies
     */
    private $aggregateId;

    /**
     * @var int
     */
    private $aggregateVersion;

    /**
     * @var Timestamp
     */
    private $tookPlaceAt;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @param EventId     $eventId
     * @param string      $eventName
     * @param DomainEvent $event
     * @param Identifies  $aggregateId
     * @param int         $aggregateVersion
     * @param Timestamp   $tookPlaceAt
     * @param Metadata    $metadata
     */
    public function __construct(
        EventId $eventId,
        $eventName,
        DomainEvent $event,
        Identifies $aggregateId,
        $aggregateVersion,
        Timestamp $tookPlaceAt,
        Metadata $metadata
    ) {
        $this->eventId          = $eventId;
        $this->eventName        = $eventName;
        $this->event            = $event;
        $this->aggregateId      = $aggregateId;
        $this->aggregateVersion = $aggregateVersion;
        $this->tookPlaceAt      = $tookPlaceAt;
        $this->metadata         = $metadata;
    }

    /**
     * @return EventId
     */
    public function eventId()
    {
        return $this->eventId;
    }

    /**
     * @return string
     */
    public function eventName()
    {
        return $this->eventName;
    }

    /**
     * @return DomainEvent
     */
    public function event()
    {
        return $this->event;
    }

    /**
     * @return Identifies
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return int
     */
    public function aggregateVersion()
    {
        return $this->aggregateVersion;
    }

    /**
     * @return Timestamp
     */
    public function tookPlaceAt()
    {
        return $this->tookPlaceAt;
    }

    /**
     * @return Metadata
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * @param Metadata $metadata
     * @return EventEnvelope
     */
    public function enrichMetadata(Metadata $metadata)
    {
        return new EventEnvelope(
            $this->eventId,
            $this->eventName,
            $this->event,
            $this->aggregateId,
            $this->aggregateVersion,
            $this->tookPlaceAt,
            $this->metadata->merge($metadata)
        );
    }
}
