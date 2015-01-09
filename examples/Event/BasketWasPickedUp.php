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

/**
 * Class BasketWasPickedUp
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class BasketWasPickedUp implements Event
{
    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @param BasketId $basketId
     */
    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->basketId;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'basketWasPickedUp';
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new BasketWasPickedUp(
            BasketId::fromString($data['basketId'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'basketId' => (string)$this->basketId
        ];
    }
}
