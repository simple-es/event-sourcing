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
use F500\EventSourcing\UnitOfWork\UnitOfWork;

/**
 * Test UnitOfWork
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class UnitOfWorkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryResolver;

    /**
     * @var BasketId
     */
    private $id;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $aggregate;

    public function setUp()
    {
        $this->repository = $this->getMockBuilder('F500\EventSourcing\Repository\Repository')->getMock();

        $this->repositoryResolver = $this->getMockBuilder('F500\EventSourcing\Repository\ResolvesRepositories')->getMock();
        $this->repositoryResolver
            ->method('resolve')
            ->will($this->returnValue($this->repository));

        $this->id = BasketId::fromString('some-id');

        $this->aggregate = $this->getMockBuilder('F500\EventSourcing\Aggregate\TracksEvents')->getMock();
        $this->aggregate
            ->method('aggregateId')
            ->will($this->returnValue($this->id));

        $this->unitOfWork = new UnitOfWork($this->repositoryResolver);
    }

    /**
     * @test
     */
    public function itTracksAnAggregate()
    {
        $this->unitOfWork->track($this->aggregate);

        $this->assertSame($this->aggregate, $this->unitOfWork->find($this->id));
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\DuplicateAggregateFound
     */
    public function itDoesNotTrackTheSameAggregateMoreThanOnce()
    {
        $this->unitOfWork->track($this->aggregate);
        $this->unitOfWork->track($this->aggregate);
    }

    /**
     * @test
     */
    public function itConsultsARepositoryWhenFindingAnAggregateThatIsNotTrackedYet()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->will($this->returnValue($this->aggregate));

        $this->assertSame($this->aggregate, $this->unitOfWork->find($this->id));
    }

    /**
     * @test
     */
    public function itConsultsARepositoryOnlyOnceWhenFindingTheSameAggregateMoreThanOnce()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->will($this->returnValue($this->aggregate));

        $this->unitOfWork->find($this->id);
        $this->unitOfWork->find($this->id);
    }

    /**
     * @test
     */
    public function itCommitsAnyChanges()
    {
        $this->aggregate
            ->method('hasRecordedEvents')
            ->will($this->returnValue(true));

        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->aggregate);

        $this->unitOfWork->track($this->aggregate);

        $this->unitOfWork->commit();
    }
}
