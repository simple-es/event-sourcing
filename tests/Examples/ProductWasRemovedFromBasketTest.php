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

use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Example\Event\ProductWasRemovedFromBasket;
use F500\EventSourcing\Example\Product\ProductId;

/**
 * Test ProductWasRemovedFromBasket
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class ProductWasRemovedFromBasketTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductWasRemovedFromBasket
     */
    private $event;

    public function setUp()
    {
        $basketId  = BasketId::fromString('basket-1');
        $productId = ProductId::fromString('product-1');

        $this->event = new ProductWasRemovedFromBasket($basketId, $productId);
    }

    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $basketId = BasketId::fromString('basket-1');

        $this->assertTrue($basketId->equals($this->event->aggregateId()));
    }

    /**
     * @test
     */
    public function itExposesAProductId()
    {
        $productId = ProductId::fromString('product-1');

        $this->assertTrue($productId->equals($this->event->productId()));
    }

    /**
     * @test
     */
    public function itExposesAName()
    {
        $this->assertSame('productWasRemovedFromBasket', $this->event->name());
    }

    /**
     * @test
     */
    public function itIsSerializable()
    {
        $serialized = ['basketId' => 'basket-1', 'productId' => 'product-1'];

        $this->assertSame($serialized, $this->event->serialize());
    }

    /**
     * @test
     */
    public function itIsDeserializable()
    {
        $deserializedEvent = ProductWasRemovedFromBasket::deserialize(
            ['basketId' => 'basket-1', 'productId' => 'product-1']
        );

        $this->assertEquals($this->event, $deserializedEvent);
    }
}
