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

use F500\EventSourcing\Event\Metadata;

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
            ['item1' => 'Some value', 'item2' => 'Other value']
        );
    }

    /**
     * @test
     */
    public function itExposesWetherAnItemExistsOrNot()
    {
        $this->assertTrue(isset($this->metadata['item1']));
        $this->assertFalse(isset($this->metadata['item3']));
    }

    /**
     * @test
     */
    public function itExposesAnItem()
    {
        $this->assertSame('Some value', $this->metadata['item1']);
    }

    /**
     * @test
     */
    public function itExposesNullWhenAnItemDoesNotExist()
    {
        $this->assertSame(null, $this->metadata['item3']);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itCannotChangeAnItem()
    {
        $this->metadata['item3'] = 'Yet another value';
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itCannotRemoveAnItem()
    {
        unset($this->metadata['item2']);
    }

    /**
     * @test
     */
    public function itMergesAnother()
    {
        $other = new Metadata(
            ['item2' => 'Second value', 'item3' => 'Yet another value']
        );

        $metadata = $this->metadata->merge($other);

        $this->assertSame('Some value', $metadata['item1']);
        $this->assertSame('Second value', $metadata['item2']);
        $this->assertSame('Yet another value', $metadata['item3']);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenAnotherIsMerged()
    {
        $other = new Metadata(
            ['item2' => 'Second value', 'item3' => 'Yet another value']
        );

        $this->metadata->merge($other);

        $this->assertSame('Some value', $this->metadata['item1']);
        $this->assertSame('Other value', $this->metadata['item2']);
        $this->assertNull($this->metadata['item3']);
    }

    /**
     * @test
     */
    public function itIsDeserializable()
    {
        $metadata = Metadata::deserialize(
            ['item1' => 'Some value', 'item2' => 'Other value']
        );

        $this->assertSame('Some value', $metadata['item1']);
        $this->assertSame('Other value', $metadata['item2']);
    }

    /**
     * @test
     */
    public function itIsSerializable()
    {
        $this->assertSame(
            ['item1' => 'Some value', 'item2' => 'Other value'],
            $this->metadata->serialize()
        );
    }
}
