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

namespace F500\EventSourcing\Example\Basket;

use F500\EventSourcing\Aggregate\EventTrackingCapabilities;
use F500\EventSourcing\Aggregate\ReconstitutesFromHistory;
use F500\EventSourcing\Aggregate\TracksEvents;
use F500\EventSourcing\Event\AggregateHistory;
use F500\EventSourcing\Example\Event\BasketWasPickedUp;
use F500\EventSourcing\Example\Event\ProductWasAddedToBasket;
use F500\EventSourcing\Example\Event\ProductWasRemovedFromBasket;
use F500\EventSourcing\Example\Product\ProductId;

/**
 * Class Basket
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class Basket implements TracksEvents, ReconstitutesFromHistory
{
    use EventTrackingCapabilities;

    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var array
     */
    private $products;

    /**
     * @var int
     */
    private $productCount;

    /**
     * {@inheritdoc}
     */
    public static function fromHistory(AggregateHistory $aggregateHistory)
    {
        $basket = new Basket();
        $basket->replayHistory($aggregateHistory);

        return $basket;
    }

    /**
     * @param BasketId $basketId
     * @return Basket
     */
    public static function pickUp(BasketId $basketId)
    {
        $basket = new Basket();
        $basket->recordThat(new BasketWasPickedUp($basketId));

        return $basket;
    }

    /**
     * @param ProductId $productId
     */
    public function addProduct(ProductId $productId)
    {
        $this->guardProductLimit();

        $this->recordThat(new ProductWasAddedToBasket($this->basketId, $productId));
    }

    /**
     * @param ProductId $productId
     */
    public function removeProduct(ProductId $productId)
    {
        if ($this->productNotInBasket($productId)) {
            return;
        }

        $this->recordThat(new ProductWasRemovedFromBasket($this->basketId, $productId));
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->basketId;
    }

    /**
     * @param BasketWasPickedUp $event
     */
    private function whenBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->basketId     = $event->aggregateId();
        $this->products     = [];
        $this->productCount = 0;
    }

    /**
     * @param ProductWasAddedToBasket $event
     */
    private function whenProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $productId = (string)$event->productId();

        if (!isset($this->products[$productId])) {
            $this->products[$productId] = 0;
        }

        $this->products[$productId]++;
        $this->productCount++;
    }

    /**
     * @param ProductWasRemovedFromBasket $event
     */
    private function whenProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        $this->products[(string)$event->productId()]--;
        $this->productCount--;
    }

    /**
     * @throws BasketLimitReached
     */
    private function guardProductLimit()
    {
        if ($this->productCount >= 3) {
            throw BasketLimitReached::create(3);
        }
    }

    /**
     * @param ProductId $productId
     * @return bool
     */
    private function productNotInBasket(ProductId $productId)
    {
        return empty($this->products[(string)$productId]);
    }

    private function __construct()
    {
    }
}
