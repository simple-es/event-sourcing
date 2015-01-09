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

namespace F500\EventSourcing\Example\Event;

use F500\EventSourcing\Event\Event;
use F500\EventSourcing\Example\Basket\BasketId;
use F500\EventSourcing\Example\Product\ProductId;

/**
 * Class ProductWasRemovedFromBasket
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class ProductWasRemovedFromBasket implements Event
{
    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @param BasketId  $basketId
     * @param ProductId $productId
     */
    public function __construct(BasketId $basketId, ProductId $productId)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->basketId;
    }

    /**
     * @return ProductId
     */
    public function productId()
    {
        return $this->productId;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'productWasRemovedFromBasket';
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new ProductWasRemovedFromBasket(
            BasketId::fromString($data['basketId']),
            ProductId::fromString($data['productId'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'basketId'  => (string)$this->basketId,
            'productId' => (string)$this->productId
        ];
    }
}
