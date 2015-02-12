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

use F500\EventSourcing\Aggregate\Identifier\IdentifiesAggregate;

/**
 * Exception AggregateHistoryIsCorrupt
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
final class AggregateHistoryIsCorrupt extends \InvalidArgumentException implements Exception
{
    /**
     * @param IdentifiesAggregate $invalidId
     * @param IdentifiesAggregate $expectedId
     * @return AggregateHistoryIsCorrupt
     */
    public static function create(IdentifiesAggregate $invalidId, IdentifiesAggregate $expectedId)
    {
        $invalidIdType  = is_object($invalidId) ? get_class($invalidId) : gettype($invalidId);
        $expectedIdType = is_object($expectedId) ? get_class($expectedId) : gettype($expectedId);

        return new AggregateHistoryIsCorrupt(
            sprintf(
                'Aggregate history can only contain events for identifier %s(%s), but got %s(%s)',
                $expectedIdType,
                (string)$expectedId,
                $invalidIdType,
                (string)$invalidId
            )
        );
    }
}
