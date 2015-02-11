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

use F500\EventSourcing\Collection\AggregateHistory;
use F500\EventSourcing\Collection\EventStream;
use F500\EventSourcing\Event\SerializableEvent;

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
     * @var SerializableEvent[]
     */
    private $recordedEvents = [];

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
        /** @var SerializableEvent $event */
        foreach ($aggregateHistory as $event) {
            $this->when($event);
        }
    }

    /**
     * @param SerializableEvent $event
     */
    private function recordThat(SerializableEvent $event)
    {
        $this->recordedEvents[] = $event;

        $this->when($event);
    }

    /**
     * @param SerializableEvent $event
     */
    private function when(SerializableEvent $event)
    {
        $method = get_class($event);

        if (($pos = strrpos($method, '\\')) !== false) {
            $method = substr($method, $pos + 1);
        }

        $method = 'when' . ucfirst($method);

        if (is_callable([$this, $method])) {
            call_user_func([$this, $method], $event);
        }
    }
}
