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

namespace F500\EventSourcing\Event;

use F500\EventSourcing\Aggregate\IdentifiesAggregate;
use F500\EventSourcing\Exception\AggregateHistoryIsCorrupt;
use F500\EventSourcing\Exception\InvalidItemInCollection;

/**
 * Class AggregateHistory
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class AggregateHistory extends EventStream
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * @param IdentifiesAggregate $aggregateId
     * @param array               $items
     */
    public function __construct(IdentifiesAggregate $aggregateId, array $items)
    {
        $this->aggregateId = $aggregateId;

        parent::__construct($items);
    }

    /**
     * @return IdentifiesAggregate
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    protected function guardItem($item)
    {
        if (!($item instanceof EventEnvelope)) {
            throw InvalidItemInCollection::create($item, 'F500\EventSourcing\Event\EventEnvelope');
        }

        if (!$item->aggregateId()->equals($this->aggregateId)) {
            throw AggregateHistoryIsCorrupt::create($item->aggregateId(), $this->aggregateId);
        }
    }
}
