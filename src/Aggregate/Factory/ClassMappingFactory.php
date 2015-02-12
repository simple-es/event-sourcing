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

namespace F500\EventSourcing\Aggregate\Factory;

use F500\EventSourcing\Aggregate\IdentifiesAggregate;
use F500\EventSourcing\Aggregate\TracksEvents;
use F500\EventSourcing\Collection\AggregateHistory;
use F500\EventSourcing\Exception\IdNotMappedToAggregate;
use F500\EventSourcing\Exception\InvalidItemInCollection;

/**
 * Class ClassMappingFactory
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class ClassMappingFactory implements ReconstitutesAggregates
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        foreach ($map as $idClass => $aggregateClass) {
            $this->guardIdClass($idClass);
            $this->guardAggregateClass($aggregateClass);

            $this->map[$idClass] = $aggregateClass;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reconstituteFromHistory(AggregateHistory $aggregateHistory)
    {
        $idClass        = get_class($aggregateHistory->aggregateId());
        $aggregateClass = $this->mapIdClassToAggregateClass($idClass);

        return call_user_func([$aggregateClass, 'fromHistory'], $aggregateHistory);
    }

    /**
     * @param string $class
     * @return string
     */
    private function mapIdClassToAggregateClass($class)
    {
        if (!isset($this->map[$class])) {
            throw IdNotMappedToAggregate::create($class);
        }

        return $this->map[$class];
    }

    /**
     * @param string $class
     * @throws InvalidItemInCollection
     */
    private function guardIdClass($class)
    {
        if (!is_string($class) || !is_subclass_of($class, 'F500\EventSourcing\Aggregate\IdentifiesAggregate')) {
            throw InvalidItemInCollection::create($class, 'F500\EventSourcing\Aggregate\IdentifiesAggregate');
        }
    }

    /**
     * @param string $class
     * @throws InvalidItemInCollection
     */
    private function guardAggregateClass($class)
    {
        if (!is_string($class) || !is_subclass_of($class, 'F500\EventSourcing\Aggregate\TracksEvents')) {
            throw InvalidItemInCollection::create($class, 'F500\EventSourcing\Aggregate\TracksEvents');
        }
    }
}
