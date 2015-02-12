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

namespace F500\EventSourcing\Test\Examples;

use F500\EventSourcing\Example\Basket\Basket;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Repository\AggregateRepository;
use F500\EventSourcing\Test\TestHelper;

/**
 * Test AggregateRepository
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class AggregateRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var AggregateRepository
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventStore;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventWrapper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregateFactory;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->eventWrapper = $this->getMock('F500\EventSourcing\Event\Wrapper\WrapsEvents');

        $this->eventStore = $this->getMock('F500\EventSourcing\EventStore\StoresEvents');

        $this->aggregateFactory = $this->getMock('F500\EventSourcing\Aggregate\Factory\ReconstitutesAggregates');

        $this->repository = new AggregateRepository($this->eventWrapper, $this->eventStore, $this->aggregateFactory);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itAddsAggregates()
    {
        $id = BasketId::fromString('some-basket');

        $eventStream    = $this->testHelper->getEventStream($id);
        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $aggregate = $this->testHelper->mockAggregate($id);
        $aggregate
            ->expects($this->once())
            ->method('recordedEvents')
            ->will($this->returnValue($eventStream));
        $aggregate
            ->expects($this->once())
            ->method('clearRecordedEvents');

        $this->eventWrapper
            ->expects($this->once())
            ->method('wrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($eventStream)
            )
            ->will($this->returnValue($envelopeStream));

        $this->eventStore
            ->expects($this->once())
            ->method('commit')
            ->with($this->equalTo($envelopeStream));

        $this->repository->add($aggregate);
    }

    /**
     * @test
     */
    public function itFindsAggregateIds()
    {
        $id = BasketId::fromString('some-basket');

        $envelopeStream   = $this->testHelper->getEnvelopeStream($id);
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = Basket::pickUp($id);

        $this->eventStore
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo($id))
            ->will($this->returnValue($envelopeStream));

        $this->eventWrapper
            ->expects($this->once())
            ->method('unwrap')
            ->with(
                $this->equalTo($id),
                $this->equalTo($envelopeStream)
            )
            ->will($this->returnValue($aggregateHistory));

        $this->aggregateFactory
            ->expects($this->once())
            ->method('reconstituteFromHistory')
            ->with($this->equalTo($aggregateHistory))
            ->will($this->returnValue($aggregate));

        $foundAggregate = $this->repository->find($id);

        $this->assertSame($aggregate, $foundAggregate);
    }
}
