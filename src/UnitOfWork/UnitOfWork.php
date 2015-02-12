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

namespace F500\EventSourcing\UnitOfWork;

use F500\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;
use F500\EventSourcing\Aggregate\TracksEvents;
use F500\EventSourcing\Exception\DuplicateAggregateFound;
use F500\EventSourcing\Repository\Repository;

/**
 * Class UnitOfWork
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class UnitOfWork implements TracksAggregates
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var TracksEvents[]
     */
    private $identityMap;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository  = $repository;
        $this->identityMap = [];
    }

    /**
     * {@inheritdoc}
     */
    public function track(TracksEvents $aggregate)
    {
        $lookupKey = $this->createLookupKey($aggregate->aggregateId());

        if (isset($this->identityMap[$lookupKey])) {
            throw DuplicateAggregateFound::create($aggregate->aggregateId());
        }

        $this->identityMap[$lookupKey] = $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function find(IdentifiesAggregate $aggregateId)
    {
        if ($this->inIdentityMap($aggregateId)) {
            return $this->getFromIdentityMap($aggregateId);
        }

        $aggregate = $this->findInRepository($aggregateId);

        $this->track($aggregate);

        return $aggregate;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        /** @var TracksEvents $aggregate */
        foreach ($this->identityMap as $aggregate) {
            if ($aggregate->hasRecordedEvents()) {
                $this->repository->add($aggregate);
            }
        }
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return bool
     */
    private function inIdentityMap(IdentifiesAggregate $aggregateId)
    {
        $lookupKey = $this->createLookupKey($aggregateId);

        return isset($this->identityMap[$lookupKey]);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    private function getFromIdentityMap(IdentifiesAggregate $aggregateId)
    {
        $lookupKey = $this->createLookupKey($aggregateId);

        return $this->identityMap[$lookupKey];
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    private function findInRepository(IdentifiesAggregate $aggregateId)
    {
        return $this->repository->find($aggregateId);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return string
     */
    private function createLookupKey(IdentifiesAggregate $aggregateId)
    {
        return sprintf('%s(%s)', get_class($aggregateId), (string)$aggregateId);
    }
}
