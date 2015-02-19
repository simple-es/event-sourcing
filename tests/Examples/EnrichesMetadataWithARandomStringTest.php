<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Examples;

use SimpleES\EventSourcing\Example\Auxiliary\EnrichesMetadataWithARandomString;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Test\TestHelper;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class EnrichesMetadataWithARandomStringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itEnrichesMetadataWithARondomString()
    {
        $id       = BasketId::fromString('some-id');
        $envelope = $this->testHelper->getEnvelopeStreamEnvelopeOne($id);

        $enricher = new EnrichesMetadataWithARandomString();

        $enrichedEnvelope = $enricher->enrich($envelope);
        $metadata         = $enrichedEnvelope->metadata();

        $this->assertArrayHasKey('random_string', $metadata);
        $this->assertInternalType('string', $metadata['random_string']);
    }
}
