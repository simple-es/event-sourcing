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

use F500\EventSourcing\EventStore\InMemoryEventStore;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Test\TestHelper;

/**
 * Test InMemoryEventStore
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class InMemoryEventStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var InMemoryEventStore
     */
    private $eventStore;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $idOne             = BasketId::fromString('id-1');
        $envelopeStreamOne = $this->testHelper->getEnvelopeStream($idOne);

        $idTwo             = BasketId::fromString('id-2');
        $envelopeStreamTwo = $this->testHelper->getEnvelopeStream($idTwo);

        $this->eventStore = new InMemoryEventStore();

        $this->eventStore->commit($envelopeStreamOne);
        $this->eventStore->commit($envelopeStreamTwo);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itGetsEventsOfASingleAggregate()
    {
        $id = BasketId::fromString('id-1');

        $envelopeStream = $this->eventStore->get($id);

        $this->assertInstanceOf('F500\EventSourcing\Collection\EventEnvelopeStream', $envelopeStream);
        $this->assertCount(3, $envelopeStream);

        $eventEnvelopeOne   = $this->testHelper->getEnvelopeStreamEnvelopeOne($id);
        $eventEnvelopeTwo   = $this->testHelper->getEnvelopeStreamEnvelopeTwo($id);
        $eventEnvelopeThree = $this->testHelper->getEnvelopeStreamEnvelopeThree($id);

        $this->assertSame($eventEnvelopeOne, $envelopeStream[0]);
        $this->assertSame($eventEnvelopeTwo, $envelopeStream[1]);
        $this->assertSame($eventEnvelopeThree, $envelopeStream[2]);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\AggregateIdNotFound
     */
    public function itFailsWhenAnAggregateIdIsNotFound()
    {
        $id = BasketId::fromString('id-3');

        $this->eventStore->get($id);
    }
}
