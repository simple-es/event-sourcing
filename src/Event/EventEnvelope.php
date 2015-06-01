<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event;

use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Timestamp\Timestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventEnvelope implements EnvelopsEvent
{
    /**
     * @var Identifies
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
     * {@inheritdoc}
     */
    public static function envelop(
        Identifies $eventId,
        $eventName,
        DomainEvent $event,
        Identifies $aggregateId,
        $aggregateVersion
    ) {
        return new static(
            $eventId,
            $eventName,
            $event,
            $aggregateId,
            $aggregateVersion,
            Timestamp::now(),
            new Metadata([])
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function fromStore(
        Identifies $eventId,
        $eventName,
        DomainEvent $event,
        Identifies $aggregateId,
        $aggregateVersion,
        Timestamp $tookPlaceAt,
        Metadata $metadata
    ) {
        return new static(
            $eventId,
            $eventName,
            $event,
            $aggregateId,
            $aggregateVersion,
            $tookPlaceAt,
            $metadata
        );
    }

    /**
     * {@inheritdoc}
     */
    public function eventId()
    {
        return $this->eventId;
    }

    /**
     * {@inheritdoc}
     */
    public function eventName()
    {
        return $this->eventName;
    }

    /**
     * {@inheritdoc}
     */
    public function event()
    {
        return $this->event;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateVersion()
    {
        return $this->aggregateVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function tookPlaceAt()
    {
        return $this->tookPlaceAt;
    }

    /**
     * {@inheritdoc}
     */
    public function metadata()
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function enrichMetadata(Metadata $metadata)
    {
        return new static(
            $this->eventId,
            $this->eventName,
            $this->event,
            $this->aggregateId,
            $this->aggregateVersion,
            $this->tookPlaceAt,
            $this->metadata->merge($metadata)
        );
    }

    /**
     * @param Identifies  $eventId
     * @param string      $eventName
     * @param DomainEvent $event
     * @param Identifies  $aggregateId
     * @param int         $aggregateVersion
     * @param Timestamp   $tookPlaceAt
     * @param Metadata    $metadata
     */
    private function __construct(
        Identifies $eventId,
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
}
