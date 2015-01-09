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

namespace F500\EventSourcing\Test\Examples;

use F500\EventSourcing\Event\AggregateHistory;
use F500\EventSourcing\Example\Basket\Basket;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Example\Product\ProductId;

/**
 * Test Basket
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class BasketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Basket
     */
    private $basket;

    public function setUp()
    {
        $basketId  = BasketId::fromString('basket-1');
        $productId = ProductId::fromString('product-1');

        $this->basket = Basket::pickUp($basketId);
        $this->basket->addProduct($productId);
        $this->basket->removeProduct($productId);
    }

    /**
     * @test
     */
    public function itExposesItRecordedEvents()
    {
        $this->assertTrue($this->basket->hasRecordedEvents());
    }

    /**
     * @test
     */
    public function itExposesAStreamOfRecordedEvents()
    {
        $this->assertInstanceOf('F500\EventSourcing\Event\EventStream', $this->basket->recordedEvents());
    }

    /**
     * @test
     */
    public function itHasThreeEvents()
    {
        $this->assertCount(3, $this->basket->recordedEvents());
    }

    /**
     * @test
     */
    public function itHasABasketWasPickedUpEvent()
    {
        $this->assertInstanceOf(
            'F500\EventSourcing\Example\Event\BasketWasPickedUp',
            $this->basket->recordedEvents()[0]->event()
        );
    }

    /**
     * @test
     */
    public function itHasAProductWasAddedToBasketEvent()
    {
        $this->assertInstanceOf(
            'F500\EventSourcing\Example\Event\ProductWasAddedToBasket',
            $this->basket->recordedEvents()[1]->event()
        );
    }

    /**
     * @test
     */
    public function itHasAProductWasRemovedFromBasketEvent()
    {
        $this->assertInstanceOf(
            'F500\EventSourcing\Example\Event\ProductWasRemovedFromBasket',
            $this->basket->recordedEvents()[2]->event()
        );
    }

    /**
     * @test
     */
    public function theEventsHaveTheCorrectPlayhead()
    {
        $this->assertSame(0, $this->basket->recordedEvents()[0]->playhead());
        $this->assertSame(1, $this->basket->recordedEvents()[1]->playhead());
        $this->assertSame(2, $this->basket->recordedEvents()[2]->playhead());
    }

    /**
     * @test
     * @expectedException \F500\EventSourcing\Example\Basket\BasketLimitReached
     */
    public function itCannotHaveMoreThanThreeProducts()
    {
        $productId = ProductId::fromString('product-1');

        $this->basket->addProduct($productId);
        $this->basket->addProduct($productId);
        $this->basket->addProduct($productId);
        $this->basket->addProduct($productId);
    }

    /**
     * @test
     */
    public function itDoesNotRecordAnEventWhenRemovedProductWasNotInBasket()
    {
        $numberOfEvents = count($this->basket->recordedEvents());

        $productId = ProductId::fromString('product-1');
        $this->basket->removeProduct($productId);

        $this->assertCount($numberOfEvents, $this->basket->recordedEvents());
    }

    /**
     * @test
     */
    public function itIsTheSameAfterReconstitution()
    {
        $events = [];
        foreach ($this->basket->recordedEvents() as $envelope) {
            $events[] = $envelope;
        }

        $this->basket->clearRecordedEvents();

        $history             = new AggregateHistory($this->basket->aggregateId(), $events);
        $reconstitutedBasket = Basket::fromHistory($history);

        $this->assertEquals($this->basket, $reconstitutedBasket);
    }
}
