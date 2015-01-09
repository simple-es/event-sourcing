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
use F500\EventSourcing\Event\EventStream;
use F500\EventSourcing\Example\Basket\BasketId;

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
     * @var EventStream
     */
    private $eventStream;

    /**
     * @var EventEnvelope
     */
    private $envelope1;

    /**
     * @var EventEnvelope
     */
    private $envelope2;

    /**
     * @var EventEnvelope
     */
    private $envelope3;

    public function setUp()
    {
        $id = BasketId::fromString('id');

        $event = $this->getMockBuilder('F500\EventSourcing\Event\Event')->getMock();
        $event
            ->method('aggregateId')
            ->will($this->returnValue($id));

        $this->envelope1 = EventEnvelope::wrap($event, 0);
        $this->envelope2 = EventEnvelope::wrap($event, 1);
        $this->envelope3 = EventEnvelope::wrap($event, 2);

        $this->eventStream = new EventStream(
            [$this->envelope1, $this->envelope2, $this->envelope3]
        );
    }

    /**
     * @test
     */
    public function itCanBeIteratedOver()
    {
        foreach ($this->eventStream as $i => $event) {
            switch ($i) {
                case 0:
                    $this->assertSame($this->envelope1, $event);
                    break;
                case 1:
                    $this->assertSame($this->envelope2, $event);
                    break;
                case 2:
                    $this->assertSame($this->envelope3, $event);
                    break;
            }
        }
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
        $this->assertSame($this->envelope1, $this->eventStream[0]);
        $this->assertSame($this->envelope2, $this->eventStream[1]);
        $this->assertSame($this->envelope3, $this->eventStream[2]);

        $this->assertNull($this->eventStream[3]);
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
     * @expectedException \F500\EventSourcing\Exception\InvalidItemInCollection
     */
    public function itContainsOnlyEventEnvelopes()
    {
        new EventStream(
            [new \stdClass()]
        );
    }
}
