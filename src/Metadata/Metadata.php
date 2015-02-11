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

namespace F500\EventSourcing\Metadata;

use F500\EventSourcing\Exception\ObjectIsImmutable;
use F500\EventSourcing\Serializer\Serializable;

/**
 * Class Metadata
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class Metadata implements \ArrayAccess, Serializable
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param Metadata $other
     * @return static
     */
    public function merge(Metadata $other)
    {
        return new Metadata(
            array_merge($this->data, $other->data)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (!isset($this->data[$offset])) {
            return null;
        }

        return $this->data[$offset];
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
        throw ObjectIsImmutable::create($this, 'offsetSet');
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new Metadata($data);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return $this->data;
    }
}
