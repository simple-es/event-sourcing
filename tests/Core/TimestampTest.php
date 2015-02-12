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

namespace F500\EventSourcing\Test\Core;

use F500\EventSourcing\Timestamp\Timestamp;

/**
 * Test Timestamp
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class TimestampTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itIsCreatedFromTheCurrentTime()
    {
        $timestamp = Timestamp::now();

        $this->assertInstanceOf('F500\EventSourcing\Timestamp\Timestamp', $timestamp);
    }

    /**
     * @test
     */
    public function itIsCreatedFromAString()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $this->assertInstanceOf('F500\EventSourcing\Timestamp\Timestamp', $timestamp);
    }

    /**
     * @test
     */
    public function itIsCreatedFromADateTimeObject()
    {
        $timestamp = Timestamp::fromDateTime(new \DateTime());

        $this->assertInstanceOf('F500\EventSourcing\Timestamp\Timestamp', $timestamp);
    }

    /**
     * @test
     */
    public function itConvertsToAString()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $this->assertSame('2014-12-23T17:30:00.000000+0000', (string)$timestamp);
    }

    /**
     * @test
     */
    public function itConvertsToAFormattedString()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $this->assertSame('Tue, 23 Dec 2014 17:30:00 +0000', $timestamp->format('r'));
    }

    /**
     * @test
     */
    public function itConvertsToADateTimeObject()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $datetime = $timestamp->toDateTime();

        $this->assertInstanceOf('DateTime', $datetime);
        $this->assertSame('Tue, 23 Dec 2014 17:30:00 +0000', $datetime->format('r'));
    }

    /**
     * @test
     */
    public function itAddsAnInterval()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $timestamp = $timestamp->add(new \DateInterval('P1DT1H'));

        $this->assertSame('2014-12-24T18:30:00.000000+0000', (string)$timestamp);
    }

    /**
     * @test
     */
    public function itSubtractsAnInterval()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $timestamp = $timestamp->sub(new \DateInterval('P1DT1H'));

        $this->assertSame('2014-12-22T16:30:00.000000+0000', (string)$timestamp);
    }

    /**
     * @test
     */
    public function itEqualsAnotherWithTheSameTime()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');
        $other     = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $this->assertTrue($timestamp->equals($other));
    }

    /**
     * @test
     */
    public function itDoesNotEqualAnotherWithADifferentTime()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');
        $other     = Timestamp::fromString('2014-12-24T18:30:00.000000+0000');

        $this->assertFalse($timestamp->equals($other));
    }

    /**
     * @test
     */
    public function itCalculatesTheDifferenceBetweenItselfAndAnother()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');
        $other     = Timestamp::fromString('2014-12-24T18:30:00.000000+0000');

        $diff = $timestamp->diff($other);

        $this->assertSame('P0Y0M1DT1H0M0S', $diff->format(Timestamp::ISO8601_INTERVAL_FORMAT));
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenTheDateTimeObjectItWasCreatedWithChanges()
    {
        $datetime = new \DateTime('2014-12-23 17:30:00', new \DateTimeZone('UTC'));

        $timestamp = Timestamp::fromDateTime($datetime);

        $datetime->add(new \DateInterval('P1D'));

        $this->assertSame('2014-12-23T17:30:00.000000+0000', (string)$timestamp);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenTheDateTimeObjectItConvertsToChanges()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $datetime = $timestamp->toDateTime();
        $datetime->add(new \DateInterval('P1D'));

        $this->assertSame('2014-12-23T17:30:00.000000+0000', (string)$timestamp);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenAddedTo()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $timestamp->add(new \DateInterval('P1DT1H'));

        $this->assertSame('2014-12-23T17:30:00.000000+0000', (string)$timestamp);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenSubtractedFrom()
    {
        $timestamp = Timestamp::fromString('2014-12-23T17:30:00.000000+0000');

        $timestamp->add(new \DateInterval('P1DT1H'));

        $this->assertSame('2014-12-23T17:30:00.000000+0000', (string)$timestamp);
    }
}
