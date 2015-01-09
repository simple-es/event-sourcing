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
 * Test ProductId
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class ProductIdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProductId
     */
    private $productId;

    public function setUp()
    {
        $this->productId = ProductId::fromString('product-1');
    }

    /**
     * @test
     */
    public function itConvertsToAString()
    {
        $this->assertSame('product-1', (string)$this->productId);
    }

    /**
     * @test
     */
    public function itEqualsAnotherWithTheSameClassAndValue()
    {
        $other = ProductId::fromString('product-1');

        $this->assertTrue($this->productId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentClass()
    {
        $other = BasketId::fromString('product-1');

        $this->assertNotTrue($this->productId->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentValue()
    {
        $other = ProductId::fromString('product-2');

        $this->assertNotTrue($this->productId->equals($other));
    }
}
