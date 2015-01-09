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

namespace F500\EventSourcing\Aggregate;

use F500\EventSourcing\Event\AggregateHistory;
use F500\EventSourcing\Event\Event;
use F500\EventSourcing\Event\EventEnvelope;
use F500\EventSourcing\Event\EventStream;

/**
 * Trait EventTrackingCapabilities
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
trait EventTrackingCapabilities
{
    /**
     * @var EventEnvelope[]
     */
    private $recordedEvents = [];

    /**
     * @var int
     */
    private $playhead = -1;

    /**
     * @return EventStream
     */
    public function recordedEvents()
    {
        return new EventStream(
            $this->recordedEvents
        );
    }

    /**
     * @return bool
     */
    public function hasRecordedEvents()
    {
        return (bool)$this->recordedEvents;
    }

    /**
     * @return void
     */
    public function clearRecordedEvents()
    {
        $this->recordedEvents = [];
    }

    /**
     * @param AggregateHistory $aggregateHistory
     */
    private function replayHistory(AggregateHistory $aggregateHistory)
    {
        /** @var EventEnvelope $envelope */
        foreach ($aggregateHistory as $envelope) {
            $this->playhead = $envelope->playhead();

            $this->when($envelope->event());
        }
    }

    /**
     * @param Event $event
     */
    private function recordThat(Event $event)
    {
        $this->recordedEvents[] = EventEnvelope::wrap(
            $event,
            ++$this->playhead
        );

        $this->when($event);
    }

    /**
     * @param Event $event
     */
    private function when(Event $event)
    {
        $method = 'when' . ucfirst($event->name());

        if (is_callable([$this, $method])) {
            $this->$method($event);
        }
    }
}
