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

namespace F500\EventSourcing\Test\Core;

use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Repository\LazyLoadingRepositoryResolver;

/**
 * Test LazyLoadingRepositoryResolver
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class LazyLoadingRepositoryResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itResolvesARepository()
    {
        $id = BasketId::fromString('some-id');

        $repository = $this->getMockBuilder('F500\EventSourcing\Repository\Repository')->getMock();

        $serviceLocator = function () use ($repository) {
            return $repository;
        };

        $repositories = [
            'F500\EventSourcing\Example\Basket\BasketId' => 'test_repository'
        ];

        $repositoryResolver = new LazyLoadingRepositoryResolver($serviceLocator, $repositories);

        $this->assertSame($repository, $repositoryResolver->resolve($id));
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\RepositoryForAggregateNotFound
     */
    public function itDoesNotResolveWhenTheRepositoryAnAggregateIdIsNotFound()
    {
        $id = BasketId::fromString('some-id');

        $serviceLocator = function () {
        };

        $repositories = [];

        $repositoryResolver = new LazyLoadingRepositoryResolver($serviceLocator, $repositories);

        $repositoryResolver->resolve($id);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\InvalidRepositoryFound
     */
    public function itDoesNotResolveWhenAnInvalidRepositoryIsFound()
    {
        $id = BasketId::fromString('some-id');

        $serviceLocator = function () {
            return new \stdClass();
        };

        $repositories = [
            'F500\EventSourcing\Example\Basket\BasketId' => 'test_repository'
        ];

        $repositoryResolver = new LazyLoadingRepositoryResolver($serviceLocator, $repositories);

        $repositoryResolver->resolve($id);
    }
}
