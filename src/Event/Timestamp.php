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

namespace F500\EventSourcing\Event;

/**
 * Class Timestamp
 *
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @license   https://github.com/f500/event-sourcing/blob/master/LICENSE MIT
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
 */
class Timestamp
{
    const ISO8601_TIME_FORMAT = 'Y-m-d\TH:i:s.uO';

    const ISO8601_INTERVAL_FORMAT = 'P%yY%mM%dDT%hH%iM%sS';

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @return Timestamp
     */
    public static function now()
    {
        return new Timestamp(
            \DateTime::createFromFormat(
                'U.u',
                sprintf('%.6f', microtime(true)),
                new \DateTimeZone('UTC')
            )
        );
    }

    /**
     * @param string $string
     * @return Timestamp
     */
    public static function fromString($string)
    {
        return new Timestamp(
            \DateTime::createFromFormat(
                self::ISO8601_TIME_FORMAT,
                $string
            )
        );
    }

    /**
     * @param \DateTime $datetime
     * @return Timestamp
     */
    public static function fromDateTime(\DateTime $datetime)
    {
        $datetime = clone $datetime;

        return new Timestamp($datetime);
    }

    /**
     * @param \DateInterval $interval
     * @return Timestamp
     */
    public function add(\DateInterval $interval)
    {
        $datetime = clone $this->datetime;
        $datetime->add($interval);

        return new Timestamp($datetime);
    }

    /**
     * @param \DateInterval $interval
     * @return Timestamp
     */
    public function sub(\DateInterval $interval)
    {
        $datetime = clone $this->datetime;
        $datetime->sub($interval);

        return new Timestamp($datetime);
    }

    /**
     * @param Timestamp $other
     * @return bool
     */
    public function equals(Timestamp $other)
    {
        return ((string)$this === (string)$other);
    }

    /**
     * @param Timestamp $other
     * @param bool      $absolute
     * @return \DateInterval
     */
    public function diff(Timestamp $other, $absolute = false)
    {
        return $this->datetime->diff($other->datetime, $absolute);
    }

    /**
     * @param string $format
     * @return string
     */
    public function format($format)
    {
        return $this->datetime->format($format);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->datetime->format(self::ISO8601_TIME_FORMAT);
    }

    /**
     * @return \DateTime
     */
    public function toDateTime()
    {
        return clone $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    private function __construct(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }
}
