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

namespace F500\EventSourcing\Event\Wrapper;

use F500\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use F500\EventSourcing\Collection\AggregateHistory;
use F500\EventSourcing\Collection\EventEnvelopeStream;
use F500\EventSourcing\Collection\EventStream;

/**
 * Interface WrapsEvents
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
interface WrapsEvents
{
    /**
     * @param IdentifiesAggregate $aggregateId
     * @param EventStream         $eventStream
     * @return EventEnvelopeStream
     */
    public function wrap(IdentifiesAggregate $aggregateId, EventStream $eventStream);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param EventEnvelopeStream $envelopeStream
     * @return AggregateHistory
     */
    public function unwrap(IdentifiesAggregate $aggregateId, EventEnvelopeStream $envelopeStream);
}
