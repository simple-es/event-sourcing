<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Test\Core;

use SimpleES\EventSourcing\Metadata\Metadata;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Metadata
     */
    private $metadata;

    public function setUp()
    {
        $this->metadata = new Metadata(
            [
                'some-key'  => 'Some value',
                'other-key' => 'Other value',
                'third-key' => 'Third value'
            ]
        );
    }

    public function tearDown()
    {
        $this->metadata = null;
    }

    /**
     * @test
     */
    public function itExposesWetherAnItemExistsOrNot()
    {
        $this->assertTrue(isset($this->metadata['some-key']));
        $this->assertTrue(isset($this->metadata['other-key']));
        $this->assertTrue(isset($this->metadata['third-key']));

        $this->assertFalse(isset($this->metadata['non-existing-key']));
    }

    /**
     * @test
     */
    public function itExposesAnItem()
    {
        $this->assertSame('Some value', $this->metadata['some-key']);
        $this->assertSame('Other value', $this->metadata['other-key']);
        $this->assertSame('Third value', $this->metadata['third-key']);
    }

    /**
     * @test
     */
    public function itExposesNullWhenAnItemDoesNotExist()
    {
        $this->assertNull($this->metadata['non-existing-key']);
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itCannotChangeAnItem()
    {
        $this->metadata['some-key'] = 'Yet another value';
    }

    /**
     * @test
     * @expectedException \SimpleES\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itCannotRemoveAnItem()
    {
        unset($this->metadata['some-key']);
    }

    /**
     * @test
     */
    public function itMergesAnother()
    {
        $other = new Metadata(
            [
                'other-key'  => 'Yet another value',
                'fourth-key' => 'Fourth value'
            ]
        );

        $mergedMetadata = $this->metadata->merge($other);

        $this->assertSame('Some value', $mergedMetadata['some-key']);
        $this->assertSame('Yet another value', $mergedMetadata['other-key']);
        $this->assertSame('Third value', $mergedMetadata['third-key']);
        $this->assertSame('Fourth value', $mergedMetadata['fourth-key']);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenAnotherIsMerged()
    {
        $other = new Metadata(
            [
                'other-key'  => 'Yet another value',
                'fourth-key' => 'Fourth value'
            ]
        );

        $this->metadata->merge($other);

        $this->assertSame('Some value', $this->metadata['some-key']);
        $this->assertSame('Other value', $this->metadata['other-key']);
        $this->assertSame('Third value', $this->metadata['third-key']);
        $this->assertNull($this->metadata['fourth-key']);
    }
}
