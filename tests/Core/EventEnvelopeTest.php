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

use F500\EventSourcing\Event\EventEnvelope;
use F500\EventSourcing\Event\Metadata;
use F500\EventSourcing\Event\Timestamp;

/**
 * Test EventEnvelope
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class EventEnvelopeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventEnvelope
     */
    private $envelope;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $event;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $metadata;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tookPlaceAt;

    public function setUp()
    {
        $this->event = $this->getMockBuilder('F500\EventSourcing\Event\Event')->getMock();

        $this->metadata = $this->getMockBuilder('F500\EventSourcing\Event\Metadata')
            ->setConstructorArgs([[]])
            ->getMock();

        $this->tookPlaceAt = Timestamp::now();

        $this->envelope = new EventEnvelope(
            $this->event,
            0,
            $this->metadata,
            $this->tookPlaceAt
        );
    }

    /**
     * @test
     */
    public function itWrapsAnEvent()
    {
        $envelope = EventEnvelope::wrap($this->event, 0);

        $this->assertInstanceOf('F500\EventSourcing\Event\EventEnvelope', $envelope);
    }

    public function itExposesAName()
    {
        $this->event
            ->expects($this->once())
            ->method('name');

        $this->envelope->name();
    }

    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $this->event
            ->expects($this->once())
            ->method('aggregateId');

        $this->envelope->aggregateId();
    }

    /**
     * @test
     */
    public function itExposesAPlayhead()
    {
        $this->assertSame(0, $this->envelope->playhead());
    }

    /**
     * @test
     */
    public function itExposesAnEvent()
    {
        $this->assertSame($this->event, $this->envelope->event());
    }

    /**
     * @test
     */
    public function itExposesMetadata()
    {
        $this->assertSame($this->metadata, $this->envelope->metadata());
    }

    /**
     * @test
     */
    public function itEnrichesMetadata()
    {
        $moreMetadata   = new Metadata([]);
        $mergedMetadata = new Metadata([]);

        $this->metadata
            ->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($moreMetadata))
            ->will($this->returnValue($mergedMetadata));

        $this->envelope->enrichMetadata($moreMetadata);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenMetadataIsenrich()
    {
        $moreMetadata   = new Metadata([]);
        $mergedMetadata = new Metadata([]);

        $this->metadata
            ->method('merge')
            ->will($this->returnValue($mergedMetadata));

        $this->envelope->enrichMetadata($moreMetadata);

        $this->assertSame($this->metadata, $this->envelope->metadata());
    }

    /**
     * @test
     */
    public function itExposesWhenItTookPlace()
    {
        $this->assertSame($this->tookPlaceAt, $this->envelope->tookPlaceAt());
    }
}
