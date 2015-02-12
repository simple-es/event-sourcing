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

use F500\EventSourcing\Aggregate\Factory\ClassMappingFactory;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Test\TestHelper;

/**
 * Test ClassMappingFactory
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class ClassMappingFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestHelper
     */
    private $testHelper;

    public function setUp()
    {
        $this->testHelper = new TestHelper($this);
    }

    public function tearDown()
    {
        $this->testHelper->tearDown();
    }

    /**
     * @test
     */
    public function itReconstituteAnAggregateFromHistory()
    {
        $factory = new ClassMappingFactory(
            ['F500\EventSourcing\Example\Basket\BasketId' => 'F500\EventSourcing\Example\Basket\Basket']
        );

        $id               = BasketId::fromString('some-id');
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $aggregate = $factory->reconstituteFromHistory($aggregateHistory);

        $this->assertInstanceOf('F500\EventSourcing\Example\Basket\Basket', $aggregate);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\IdNotMappedToAggregate
     */
    public function itCannotReconstituteAnAggregateWhenMapNotFound()
    {
        $factory = new ClassMappingFactory([]);

        $id               = BasketId::fromString('some-id');
        $aggregateHistory = $this->testHelper->getAggregateHistory($id);

        $factory->reconstituteFromHistory($aggregateHistory);
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\InvalidItemInCollection
     */
    public function theMapKeysMustBeClassNamesImplementingIdentifiesAggregate()
    {
        new ClassMappingFactory(
            ['stdClass' => 'F500\EventSourcing\Example\Basket\Basket']
        );
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Exception\InvalidItemInCollection
     */
    public function theMapValuesMustBeClassNamesImplementingTracksEvents()
    {
        new ClassMappingFactory(
            ['F500\EventSourcing\Example\Basket\BasketId' => 'stdClass']
        );
    }
}
