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

use F500\EventSourcing\Aggregate\IdentifiesAggregate;
use F500\EventSourcing\Aggregate\TracksEvents;
use F500\EventSourcing\Exception\DuplicateAggregateFound;
use F500\EventSourcing\Repository\ResolvesRepositories;

/**
 * Class UnitOfWork
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class UnitOfWork implements TracksAggregates
{
    /**
     * @var ResolvesRepositories
     */
    private $repositoryResolver;

    /**
     * @var TracksEvents[]
     */
    private $identityMap;

    /**
     * @param ResolvesRepositories $repositoryResolver
     */
    public function __construct(ResolvesRepositories $repositoryResolver)
    {
        $this->repositoryResolver = $repositoryResolver;
        $this->identityMap        = [];
    }

    /**
     * {@inheritdoc}
     */
    public function track(TracksEvents $aggregate)
    {
        $key = $this->createLookupKey($aggregate->aggregateId());

        if (isset($this->identityMap[$key])) {
            throw DuplicateAggregateFound::create($aggregate->aggregateId());
        }

        $this->identityMap[$key] = $aggregate;
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
        foreach ($this->identityMap as $aggregate) {
            if ($aggregate->hasRecordedEvents()) {
                $repository = $this->repositoryResolver->resolve($aggregate->aggregateId());
                $repository->add($aggregate);
            }
        }
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return bool
     */
    private function inIdentityMap(IdentifiesAggregate $aggregateId)
    {
        $key = $this->createLookupKey($aggregateId);

        return isset($this->identityMap[$key]);
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    private function getFromIdentityMap(IdentifiesAggregate $aggregateId)
    {
        $key = $this->createLookupKey($aggregateId);

        if (!isset($this->identityMap[$key])) {
            return null;
        }

        return $this->identityMap[$key];
    }

    /**
     * @param IdentifiesAggregate $aggregateId
     * @return TracksEvents
     */
    private function findInRepository(IdentifiesAggregate $aggregateId)
    {
        $repository = $this->repositoryResolver->resolve($aggregateId);

        return $repository->find($aggregateId);
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
