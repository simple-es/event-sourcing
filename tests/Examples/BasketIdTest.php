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
use F500\EventSourcing\Example\Product\ProductId;

/**
 * Test BasketId
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class BasketIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BasketId
     */
    private $basketId;

    public function setUp()
    {
        $this->basketId = BasketId::fromString('basket-1');
    }

    /**
     * @test
     */
    public function itConvertsToAString()
    {
        $this->assertSame('basket-1', (string)$this->basketId);
    }

    /**
     * @test
     */
    public function itEqualsAnotherWithTheSameClassAndValue()
    {
        $other = BasketId::fromString('basket-1');

        $this->assertTrue($this->basketId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentClass()
    {
        $other = ProductId::fromString('basket-1');

        $this->assertNotTrue($this->basketId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentValue()
    {
        $other = BasketId::fromString('basket-2');

        $this->assertNotTrue($this->basketId->equals($other));
    }
}
