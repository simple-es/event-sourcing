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
use F500\EventSourcing\Exception\InvalidRepositoryFound;
use F500\EventSourcing\Exception\RepositoryForAggregateNotFound;

/**
 * Class LazyLoadingRepositoryResolver
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class LazyLoadingRepositoryResolver implements ResolvesRepositories
{
    /**
     * @var callable
     */
    private $serviceLocator;

    /**
     * @var Repository[]
     */
    private $repositories;

    /**
     * @param callable $serviceLocator
     * @param array    $repositories
     */
    public function __construct(callable $serviceLocator, array $repositories)
    {
        $this->serviceLocator = $serviceLocator;
        $this->repositories   = $repositories;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(IdentifiesAggregate $aggregateId)
    {
        $typeOfAggregateId = get_class($aggregateId);

        if (!isset($this->repositories[$typeOfAggregateId])) {
            throw RepositoryForAggregateNotFound::create($typeOfAggregateId);
        }

        $serviceId = $this->repositories[$typeOfAggregateId];

        $repository = call_user_func($this->serviceLocator, $serviceId);

        if (!($repository instanceof Repository)) {
            throw InvalidRepositoryFound::create($repository, 'F500\EventSourcing\Repository\Repository');
        }

        return $repository;
    }
}
