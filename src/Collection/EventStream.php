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

use F500\EventSourcing\Event\SerializableEvent;
use F500\EventSourcing\Exception\CollectionIsEmpty;
use F500\EventSourcing\Exception\InvalidItemInCollection;

/**
 * Class EventStream
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class EventStream extends Collection
{
    /**
     * {@inheritdoc}
     */
    protected function guardItem($item)
    {
        if (!($item instanceof SerializableEvent)) {
            throw InvalidItemInCollection::create($item, 'F500\EventSourcing\Event\SerializableEvent');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function guardAmountOfItems($amount)
    {
        if ($amount === 0) {
            throw CollectionIsEmpty::create();
        }
    }
}
