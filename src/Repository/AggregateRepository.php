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

namespace F500\EventSourcing\Repository;

use F500\EventSourcing\Aggregate\IdentifiesAggregate;
use F500\EventSourcing\Aggregate\TracksEvents;
use F500\EventSourcing\Collection\AggregateHistory;
use F500\EventSourcing\Event\WrapsEvents;
use F500\EventSourcing\EventStore\StoresEvents;

/**
 * Class AggregateRepository
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
abstract class AggregateRepository implements Repository
{
    /**
     * @var WrapsEvents
     */
    private $eventWrapper;

    /**
     * @var StoresEvents
     */
    private $eventStore;

    /**
     * @param WrapsEvents  $eventWrapper
     * @param StoresEvents $eventStore
     */
    public function __construct(WrapsEvents $eventWrapper, StoresEvents $eventStore)
    {
        $this->eventWrapper = $eventWrapper;
        $this->eventStore   = $eventStore;
    }

    /**
     * {@inheritdoc}
     */
    public function add(TracksEvents $aggregate)
    {
        $recordedEvents = $aggregate->recordedEvents();
        $aggregate->clearRecordedEvents();

        $envelopeStream = $this->eventWrapper->wrap($aggregate->aggregateId(), $recordedEvents);

        $this->eventStore->commit($envelopeStream);
    }

    /**
     * {@inheritdoc}
     */
    public function find(IdentifiesAggregate $aggregateId)
    {
        $envelopeStream = $this->eventStore->get($aggregateId);
        $history        = $this->eventWrapper->unwrap($aggregateId, $envelopeStream);

        return $this->reconstituteAggregate($history);
    }

    /**
     * @param AggregateHistory $aggregateHistory
     * @return TracksEvents
     */
    abstract protected function reconstituteAggregate(AggregateHistory $aggregateHistory);
}
