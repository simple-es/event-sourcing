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

use F500\EventSourcing\Event\AggregateHistory;
use F500\EventSourcing\Event\EventEnvelope;
use F500\EventSourcing\Example\Basket\BasketId;

/**
 * Test AggregateHistory
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class AggregateHistoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $id = BasketId::fromString('id');

        $aggregateHistory = new AggregateHistory($id, []);

        $this->assertSame($id, $aggregateHistory->aggregateId());
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\AggregateHistoryIsCorrupt
     */
    public function itContainsOnlyEventEnvelopesWithTheSameAggregateIdAsItself()
    {
        $id      = BasketId::fromString('id');
        $otherId = BasketId::fromString('other-id');

        $event = $this->getMockBuilder('F500\EventSourcing\Event\Event')->getMock();
        $event
            ->method('aggregateId')
            ->will($this->returnValue($otherId));

        $envelope = EventEnvelope::wrap($event, 0);

        new AggregateHistory($id, [$envelope]);
    }
}
