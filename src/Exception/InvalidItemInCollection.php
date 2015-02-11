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

namespace F500\EventSourcing\Exception;

/**
 * Exception InvalidItemInCollection
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class InvalidItemInCollection extends \InvalidArgumentException implements Exception
{
    /**
     * @param mixed  $invalidItem
     * @param string $expectedType
     * @return InvalidItemInCollection
     */
    public static function create($invalidItem, $expectedType)
    {
        $invalidType = is_object($invalidItem) ? get_class($invalidItem) : gettype($invalidItem);

        return new InvalidItemInCollection(
            sprintf(
                'Collection can only contain items of type %s, but got %s',
                $expectedType,
                $invalidType
            )
        );
    }
}
