<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Auxiliary;

use SimpleES\EventSourcing\Event\EnvelopsEvent;
use SimpleES\EventSourcing\Metadata\EnrichesMetadata;
use SimpleES\EventSourcing\Metadata\Metadata;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EnrichesMetadataWithARandomString implements EnrichesMetadata
{
    /**
     * {@inheritdoc}
     */
    public function enrich(EnvelopsEvent $envelope)
    {
        $randomString = base64_encode(openssl_random_pseudo_bytes(48));

        return $envelope->enrichMetadata(
            new Metadata(['random_string' => $randomString])
        );
    }
}
