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

namespace F500\EventSourcing\EventStore;

use F500\EventSourcing\Aggregate\IdentifiesAggregate;
use F500\EventSourcing\Collection\EventEnvelopeStream;
use F500\EventSourcing\Event\EventEnvelope;
use F500\EventSourcing\Exception\AggregateIdNotFound;

/**
 * Class InMemoryEventStore
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class InMemoryEventStore implements StoresEvents
{
    /**
     * @var EventEnvelope[]
     */
    private $store;

    /**
     * {@inheritdoc}
     */
    public function commit(EventEnvelopeStream $envelopeStream)
    {
        foreach ($envelopeStream as $eventEnvelope) {
            $this->store[] = $eventEnvelope;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(IdentifiesAggregate $aggregateId)
    {
        $eventEnvelopes = [];

        foreach ($this->store as $eventEnvelope) {
            if ($eventEnvelope->aggregateId()->equals($aggregateId)) {
                $eventEnvelopes[] = $eventEnvelope;
            }
        }

        if (!$eventEnvelopes) {
            throw AggregateIdNotFound::create($aggregateId);
        }

        return new EventEnvelopeStream($eventEnvelopes);
    }
}
