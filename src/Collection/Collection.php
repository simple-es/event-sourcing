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

namespace F500\EventSourcing\Collection;

use F500\EventSourcing\Exception\Exception;
use F500\EventSourcing\Exception\ObjectIsImmutable;

/**
 * Abstract class Collection
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
abstract class Collection implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $position;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $item) {
            $this->guardItem($item);

            $this->items[] = $item;
        }

        $this->guardAmountOfItems(count($this->items));

        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (!isset($this->items[$offset])) {
            return null;
        }

        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     * @throws ObjectIsImmutable
     */
    public function offsetSet($offset, $value)
    {
        throw ObjectIsImmutable::create($this, 'offsetSet');
    }

    /**
     * {@inheritdoc}
     * @throws ObjectIsImmutable
     */
    public function offsetUnset($offset)
    {
        throw ObjectIsImmutable::create($this, 'offsetUnset');
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->items[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @param mixed $item
     * @return void
     * @throws Exception
     */
    abstract protected function guardItem($item);

    /**
     * @param int $amount
     * @return void
     * @throws Exception
     */
    abstract protected function guardAmountOfItems($amount);
}
