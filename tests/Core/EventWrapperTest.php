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

use F500\EventSourcing\Event\Wrapper\EventWrapper;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Test\TestHelper;

/**
 * Test EventWrapper
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class EventWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var EventWrapper
     */
    private $eventWrapper;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $this->eventWrapper = new EventWrapper();
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itConvertsAnEventStreamToAnEventEnvelopeStream()
    {
        $id          = BasketId::fromString('some-id');
        $eventStream = $this->testHelper->getEventStream($id);

        $envelopeStream = $this->eventWrapper->wrap($id, $eventStream);

        $this->assertInstanceOf('F500\EventSourcing\Collection\EventEnvelopeStream', $envelopeStream);
        $this->assertCount(3, $envelopeStream);
    }

    /**
     * @test
     */
    public function itConvertsAnEventEnvelopeStreamToAnAggregateHistory()
    {
        $id             = BasketId::fromString('some-id');
        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $aggregateHistory = $this->eventWrapper->unwrap($id, $envelopeStream);

        $this->assertInstanceOf('F500\EventSourcing\Collection\AggregateHistory', $aggregateHistory);
        $this->assertCount(3, $aggregateHistory);
    }

    /**
     * @test
     */
    public function itMaintainsConsecutivePlayhead()
    {
        $id          = BasketId::fromString('some-id');
        $eventStream = $this->testHelper->getEventStream($id);

        $envelopeStream = $this->eventWrapper->wrap($id, $eventStream);

        $this->assertSame(0, $envelopeStream[0]->playhead());
        $this->assertSame(1, $envelopeStream[1]->playhead());
        $this->assertSame(2, $envelopeStream[2]->playhead());
    }

    /**
     * @test
     */
    public function itMaintainsConsecutivePlayheadAfterUnwrapping()
    {
        $id             = BasketId::fromString('some-id');
        $eventStream    = $this->testHelper->getEventStream($id);
        $envelopeStream = $this->testHelper->getEnvelopeStream($id);

        $this->eventWrapper->unwrap($id, $envelopeStream);

        $newEnvelopeStream = $this->eventWrapper->wrap($id, $eventStream);

        $this->assertSame(3, $newEnvelopeStream[0]->playhead());
        $this->assertSame(4, $newEnvelopeStream[1]->playhead());
        $this->assertSame(5, $newEnvelopeStream[2]->playhead());
    }
}
