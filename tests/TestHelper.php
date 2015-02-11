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

namespace F500\EventSourcing\Test;

use F500\EventSourcing\Aggregate\IdentifiesAggregate;
use F500\EventSourcing\Collection\AggregateHistory;
use F500\EventSourcing\Collection\EventEnvelopeStream;
use F500\EventSourcing\Collection\EventStream;
use F500\EventSourcing\Event\EventEnvelope;

/**
 * Class TestHelper
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class TestHelper
{
    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private $testCase;

    private $eventStream;
    private $eventStreamEventOne;
    private $eventStreamEventTwo;
    private $eventStreamEventThree;

    private $aggregateHistory;
    private $aggregateHistoryEventOne;
    private $aggregateHistoryEventTwo;
    private $aggregateHistoryEventThree;

    private $envelopeStream;
    private $envelopeStreamEnvelopeOne;
    private $envelopeStreamEnvelopeTwo;
    private $envelopeStreamEnvelopeThree;
    private $envelopeStreamEventOne;
    private $envelopeStreamEventTwo;
    private $envelopeStreamEventThree;

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(\PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function tearDown()
    {
        $this->eventStream           = null;
        $this->eventStreamEventOne   = null;
        $this->eventStreamEventTwo   = null;
        $this->eventStreamEventThree = null;

        $this->aggregateHistory           = null;
        $this->aggregateHistoryEventOne   = null;
        $this->aggregateHistoryEventTwo   = null;
        $this->aggregateHistoryEventThree = null;

        $this->envelopeStream              = null;
        $this->envelopeStreamEnvelopeOne   = null;
        $this->envelopeStreamEnvelopeTwo   = null;
        $this->envelopeStreamEnvelopeThree = null;
        $this->envelopeStreamEventOne      = null;
        $this->envelopeStreamEventTwo      = null;
        $this->envelopeStreamEventThree    = null;
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventStream
     */
    public function getEventStream(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStream[$key])) {
            $this->eventStream[$key] = new EventStream(
                [
                    $this->getEventStreamEventOne($id),
                    $this->getEventStreamEventTwo($id),
                    $this->getEventStreamEventThree($id)
                ]
            );
        }

        return $this->eventStream[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventOne[$key])) {
            $this->eventStreamEventOne[$key] = $this->mockEvent($id);
        }

        return $this->eventStreamEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventTwo[$key])) {
            $this->eventStreamEventTwo[$key] = $this->mockEvent($id);
        }

        return $this->eventStreamEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEventStreamEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->eventStreamEventThree[$key])) {
            $this->eventStreamEventThree[$key] = $this->mockEvent($id);
        }

        return $this->eventStreamEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return AggregateHistory
     */
    public function getAggregateHistory(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistory[$key])) {
            $this->aggregateHistory[$key] = new AggregateHistory(
                $id,
                [
                    $this->getAggregateHistoryEventOne($id),
                    $this->getAggregateHistoryEventTwo($id),
                    $this->getAggregateHistoryEventThree($id)
                ]
            );
        }

        return $this->aggregateHistory[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistoryEventOne[$key])) {
            $this->aggregateHistoryEventOne[$key] = $this->mockEvent($id);
        }

        return $this->aggregateHistoryEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistoryEventTwo[$key])) {
            $this->aggregateHistoryEventTwo[$key] = $this->mockEvent($id);
        }

        return $this->aggregateHistoryEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getAggregateHistoryEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->aggregateHistoryEventThree[$key])) {
            $this->aggregateHistoryEventThree[$key] = $this->mockEvent($id);
        }

        return $this->aggregateHistoryEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelopeStream
     */
    public function getEnvelopeStream(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStream[$key])) {
            $this->envelopeStream[$key] = new EventEnvelopeStream(
                [
                    $this->getEnvelopeStreamEnvelopeOne($id),
                    $this->getEnvelopeStreamEnvelopeTwo($id),
                    $this->getEnvelopeStreamEnvelopeThree($id)
                ]
            );
        }

        return $this->envelopeStream[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEnvelopeStreamEnvelopeOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEnvelopeOne[$key])) {
            $this->envelopeStreamEnvelopeOne[$key] = EventEnvelope::wrap(
                $this->getEnvelopeStreamEventOne($id),
                0
            );
        }

        return $this->envelopeStreamEnvelopeOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEnvelopeStreamEnvelopeTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEnvelopeTwo[$key])) {
            $this->envelopeStreamEnvelopeTwo[$key] = EventEnvelope::wrap(
                $this->getEnvelopeStreamEventTwo($id),
                1
            );
        }

        return $this->envelopeStreamEnvelopeTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return EventEnvelope
     */
    public function getEnvelopeStreamEnvelopeThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEnvelopeThree[$key])) {
            $this->envelopeStreamEnvelopeThree[$key] = EventEnvelope::wrap(
                $this->getEnvelopeStreamEventThree($id),
                2
            );
        }

        return $this->envelopeStreamEnvelopeThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEnvelopeStreamEventOne(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEventOne[$key])) {
            $this->envelopeStreamEventOne[$key] = $this->mockEvent($id);
        }

        return $this->envelopeStreamEventOne[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEnvelopeStreamEventTwo(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEventTwo[$key])) {
            $this->envelopeStreamEventTwo[$key] = $this->mockEvent($id);
        }

        return $this->envelopeStreamEventTwo[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getEnvelopeStreamEventThree(IdentifiesAggregate $id)
    {
        $key = (string)$id;

        if (!isset($this->envelopeStreamEventThree[$key])) {
            $this->envelopeStreamEventThree[$key] = $this->mockEvent($id);
        }

        return $this->envelopeStreamEventThree[$key];
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEvent(IdentifiesAggregate $id)
    {
        $class = 'F500\EventSourcing\Event\SerializableEvent';

        $event = $this->testCase->getMockBuilder($class)->getMock();
        $event
            ->method('aggregateId')
            ->will($this->testCase->returnValue($id));

        return $event;
    }

    /**
     * @param IdentifiesAggregate $id
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockAggregate(IdentifiesAggregate $id)
    {
        $class = 'F500\EventSourcing\Aggregate\TracksEvents';

        $aggregate = $this->testCase->getMockBuilder($class)->getMock();
        $aggregate
            ->method('aggregateId')
            ->will($this->testCase->returnValue($id));

        return $aggregate;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEventStoreMiddlewareForCommit()
    {
        $class = 'F500\EventSourcing\EventStore\Middleware\EventStoreMiddleware';

        $middleware = $this->testCase->getMockBuilder($class)->getMock();
        $middleware
            ->expects($this->testCase->once())
            ->method('commit')
            ->will(
                $this->testCase->returnCallback(
                    function (EventEnvelopeStream $envelopeStream, callable $next) {
                        $next($envelopeStream);
                    }
                )
            );

        return $middleware;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockEventStoreMiddlewareForGet()
    {
        $class = 'F500\EventSourcing\EventStore\Middleware\EventStoreMiddleware';

        $middleware = $this->testCase->getMockBuilder($class)->getMock();
        $middleware
            ->expects($this->testCase->once())
            ->method('get')
            ->will(
                $this->testCase->returnCallback(
                    function (IdentifiesAggregate $aggregateId, callable $next) {
                        return $next($aggregateId);
                    }
                )
            );

        return $middleware;
    }
}
