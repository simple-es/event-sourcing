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

use F500\EventSourcing\Collection\EventStream;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Test\TestHelper;

/**
 * Test EventStream
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class EventStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    /**
     * @var EventStream
     */
    private $eventStream;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);

        $id = BasketId::fromString('some-id');

        $this->eventStream = new EventStream(
            [
                $this->testHelper->getEventStreamEventOne($id),
                $this->testHelper->getEventStreamEventTwo($id),
                $this->testHelper->getEventStreamEventThree($id)
            ]
        );
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\InvalidItemInCollection
     */
    public function itContainsOnlyEventEnvelopes()
    {
        new EventStream(
            [new \stdClass()]
        );
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\CollectionIsEmpty
     */
    public function itCannotBeEmpty()
    {
        new EventStream(
            []
        );
    }

    /**
     * @test
     */
    public function itExposesWhetherAKeyExistsOrNot()
    {
        $this->assertTrue(isset($this->eventStream[0]));
        $this->assertTrue(isset($this->eventStream[1]));
        $this->assertTrue(isset($this->eventStream[2]));

        $this->assertFalse(isset($this->eventStream[3]));
    }

    /**
     * @test
     */
    public function itExposesItemsByKey()
    {
        $id = BasketId::fromString('some-id');

        $eventOne   = $this->testHelper->getEventStreamEventOne($id);
        $eventTwo   = $this->testHelper->getEventStreamEventTwo($id);
        $eventThree = $this->testHelper->getEventStreamEventThree($id);

        $this->assertSame($eventOne, $this->eventStream[0]);
        $this->assertSame($eventTwo, $this->eventStream[1]);
        $this->assertSame($eventThree, $this->eventStream[2]);

        $this->assertNull($this->eventStream[3]);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itemsCannotBeReplaced()
    {
        $id    = BasketId::fromString('some-id');
        $event = $this->testHelper->getEventStreamEventOne($id);

        $this->eventStream[0] = $event;
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\ObjectIsImmutable
     */
    public function itemsCannotBeRemoved()
    {
        unset($this->eventStream[0]);
    }

    /**
     * @test
     */
    public function itCanBeCounted()
    {
        $this->assertCount(3, $this->eventStream);
    }

    /**
     * @test
     */
    public function itCanBeIteratedOver()
    {
        foreach ($this->eventStream as $event) {
            $this->assertInstanceOf('F500\EventSourcing\Event\SerializableEvent', $event);
        }
    }

    /**
     * @test
     */
    public function itCanBeIterateOverWithIndexes()
    {
        foreach ($this->eventStream as $index => $event) {
            $this->assertInternalType('int', $index);
        }
    }
}
