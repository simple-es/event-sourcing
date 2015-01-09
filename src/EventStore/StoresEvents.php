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
use F500\EventSourcing\Event\AggregateHistory;
use F500\EventSourcing\Event\EventStream;
use F500\EventSourcing\Exception\AggregateIdNotFound;

/**
 * Interface StoresEvents
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
interface StoresEvents
{
    /**
     * @param EventStream $eventStream
     * @return void
     */
    public function commit(EventStream $eventStream);

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return AggregateHistory
     * @throws AggregateIdNotFound
     */
    public function findHistoryFor(IdentifiesAggregate $aggregateId);
}
