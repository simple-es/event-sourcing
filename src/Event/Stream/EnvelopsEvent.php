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
interface EnvelopsEvent
{
    /**
     * @param Identifies  $eventId
     * @param string      $eventName
     * @param DomainEvent $event
     * @param Identifies  $aggregateId
     * @param int         $aggregateVersion
     * @return static
     */
    public static function envelop(
        Identifies $eventId,
        $eventName,
        DomainEvent $event,
        Identifies $aggregateId,
        $aggregateVersion
    );

    /**
     * @param Identifies  $eventId
     * @param string      $eventName
     * @param DomainEvent $event
     * @param Identifies  $aggregateId
     * @param int         $aggregateVersion
     * @param Timestamp   $tookPlaceAt
     * @param Metadata    $metadata
     * @return static
     */
    public static function fromStore(
        Identifies $eventId,
        $eventName,
        DomainEvent $event,
        Identifies $aggregateId,
        $aggregateVersion,
        Timestamp $tookPlaceAt,
        Metadata $metadata
    );

    /**
     * @return Identifies
     */
    public function eventId();

    /**
     * @return string
     */
    public function eventName();

    /**
     * @return DomainEvent
     */
    public function event();

    /**
     * @return Identifies
     */
    public function aggregateId();

    /**
     * @return int
     */
    public function aggregateVersion();

    /**
     * @return Timestamp
     */
    public function tookPlaceAt();

    /**
     * @return Metadata
     */
    public function metadata();

    /**
     * @param Metadata $metadata
     * @return static
     */
    public function enrichMetadata(Metadata $metadata);
}
