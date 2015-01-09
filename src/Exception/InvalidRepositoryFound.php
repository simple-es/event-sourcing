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
 * Exception InvalidRepositoryFound
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class InvalidRepositoryFound extends \InvalidArgumentException implements Exception
{
    /**
     * @param mixed  $invalidObject
     * @param string $expectedType
     * @return InvalidRepositoryFound
     */
    public static function create($invalidObject, $expectedType)
    {
        $invalidType = is_object($invalidObject) ? get_class($invalidObject) : gettype($invalidObject);

        return new InvalidRepositoryFound(
            sprintf(
                'Expected a repository of type %s, but got %s',
                $expectedType,
                $invalidType
            )
        );
    }
}
