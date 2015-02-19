<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Auxiliary;

use SimpleES\EventSourcing\Event\EventEnvelope;
use SimpleES\EventSourcing\Metadata\EnrichesMetadata;
use SimpleES\EventSourcing\Metadata\Metadata;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class EnrichesMetadataWithARandomString implements EnrichesMetadata
{
    /**
     * @param EventEnvelope $eventEnvelope
     * @return EventEnvelope
     */
    public function enrich(EventEnvelope $eventEnvelope)
    {
        $randomString = base64_encode(openssl_random_pseudo_bytes(48));

        return $eventEnvelope->enrichMetadata(
            new Metadata(['random_string' => $randomString])
        );
    }
}
