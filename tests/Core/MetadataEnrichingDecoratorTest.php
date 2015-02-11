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

use F500\EventSourcing\EventStore\Decorator\MetadataEnrichingDecorator;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Test\TestHelper;

/**
 * Test MetadataEnrichingDecorator
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class MetadataEnrichingDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var MetadataEnrichingDecorator
     */
    private $eventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $nextEventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $metadataEnricher;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->nextEventStore = $this->getMockBuilder('F500\EventSourcing\EventStore\StoresEvents')->getMock();

        $this->metadataEnricher = $this->getMockBuilder('F500\EventSourcing\Metadata\EnrichesMetadata')->getMock();

        $this->eventStore = new MetadataEnrichingDecorator([$this->metadataEnricher], $this->nextEventStore);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itEnrichesMetadataWhenEventsAreCommittedBeforePassingThemToTheNextEventStore()
    {
        $id = BasketId::fromString('some-id');

        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $eventEnvelopeOne   = $this->testHelper->getEnvelopeStreamEnvelopeOne($id);
        $eventEnvelopeTwo   = $this->testHelper->getEnvelopeStreamEnvelopeTwo($id);
        $eventEnvelopeThree = $this->testHelper->getEnvelopeStreamEnvelopeThree($id);

        $this->metadataEnricher
            ->expects($this->exactly(3))
            ->method('enrich')
            ->withConsecutive(
                [$this->equalTo($eventEnvelopeOne)],
                [$this->equalTo($eventEnvelopeTwo)],
                [$this->equalTo($eventEnvelopeThree)]
            )
            ->will($this->returnArgument(0));

        $this->nextEventStore
            ->expects($this->once())
            ->method('commit')
            ->with($this->isInstanceOf('F500\EventSourcing\Collection\EventEnvelopeStream'));

        $this->eventStore->commit($envelopeStream);
    }

    /**
     * @test
     */
    public function itSimplyProxiesGettingEventsToTheNextEventStore()
    {
        $id = BasketId::fromString('some-id');

        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $this->nextEventStore
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($envelopeStream));

        $returnedEnvelopeStream = $this->eventStore->get($id);

        $this->assertSame($envelopeStream, $returnedEnvelopeStream);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\InvalidItemInCollection
     */
    public function itsListOfMetadataEnrichersMustBeOfTheCorrectType()
    {
        new MetadataEnrichingDecorator([new \stdClass()], $this->nextEventStore);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itsListOfMetadataEnrichersCannotBeEmpty()
    {
        new MetadataEnrichingDecorator([], $this->nextEventStore);
    }
}
