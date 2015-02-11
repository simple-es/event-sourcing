<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * For more information, please view the LICENSE file that was distributed with
 * this source code.
 */

namespace F500\EventSourcing\Test\Core;

use F500\EventSourcing\Metadata\Metadata;

/**
 * Test Metadata
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
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
     * @expectedException \F500\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itCannotChangeAnItem()
    {
        $this->metadata['some-key'] = 'Yet another value';
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\ObjectIsImmutable
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

    /**
     * @test
     */
    public function itIsDeserializable()
    {
        $deserializedMetadata = Metadata::deserialize(
            [
                'some-key'  => 'Some value',
                'other-key' => 'Other value',
                'third-key' => 'Third value'
            ]
        );

        $this->assertSame('Some value', $deserializedMetadata['some-key']);
        $this->assertSame('Other value', $deserializedMetadata['other-key']);
        $this->assertSame('Third value', $deserializedMetadata['third-key']);
    }

    /**
     * @test
     */
    public function itIsSerializable()
    {
        $serialized = [
            'some-key'  => 'Some value',
            'other-key' => 'Other value',
            'third-key' => 'Third value'
        ];

        $this->assertSame($serialized, $this->metadata->serialize());
    }
}
