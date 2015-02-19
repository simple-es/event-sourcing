<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Event;

use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\EventSourcing\Timestamp\Timestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class EventEnvelope implements Event
{
    /**
     * @var SerializableEvent
     */
    private $event;

    /**
     * @var int
     */
    private $playhead;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @var Timestamp
     */
    private $tookPlaceAt;

    /**
     * @param SerializableEvent $event
     * @param int               $playhead
     * @param Metadata          $metadata
     * @param Timestamp         $tookPlaceAt
     */
    public function __construct(SerializableEvent $event, $playhead, Metadata $metadata, Timestamp $tookPlaceAt)
    {
        $this->event       = $event;
        $this->playhead    = (int)$playhead;
        $this->metadata    = $metadata;
        $this->tookPlaceAt = $tookPlaceAt;
    }

    /**
     * @param SerializableEvent $event
     * @param int               $playhead
     * @return EventEnvelope
     */
    public static function wrap(SerializableEvent $event, $playhead)
    {
        return new EventEnvelope(
            $event,
            $playhead,
            new Metadata([]),
            Timestamp::now()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->event->aggregateId();
    }

    /**
     * @return SerializableEvent
     */
    public function event()
    {
        return $this->event;
    }

    /**
     * @return int
     */
    public function playhead()
    {
        return $this->playhead;
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
            $this->event,
            $this->playhead,
            $this->metadata->merge($metadata),
            $this->tookPlaceAt
        );
    }

    /**
     * @return Timestamp
     */
    public function tookPlaceAt()
    {
        return $this->tookPlaceAt;
    }
}
