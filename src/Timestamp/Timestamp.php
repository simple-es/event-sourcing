<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Timestamp;

use SimpleES\EventSourcing\Exception\InvalidTimestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class Timestamp
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
        return self::fromFormat(
            'U.u',
            sprintf('%.6f', microtime(true)),
            new \DateTimeZone('UTC')
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
     * @param string $string
     * @return Timestamp
     */
    public static function fromString($string)
    {
        return self::fromFormat(
            self::ISO8601_TIME_FORMAT,
            $string
        );
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
    public function subtract(\DateInterval $interval)
    {
        $datetime = clone $this->datetime;
        $datetime->sub($interval);

        return new Timestamp($datetime);
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other)
    {
        if (!$other instanceof Timestamp) {
            return false;
        }

        return $this->toString() === $other->toString();
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
     * @return \DateTime
     */
    public function toDateTime()
    {
        return clone $this->datetime;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->datetime->format(self::ISO8601_TIME_FORMAT);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string        $format
     * @param string        $time
     * @param \DateTimeZone $timezone
     * @return Timestamp
     */
    private static function fromFormat($format, $time, \DateTimeZone $timezone = null)
    {
        // Contrary to what the docs say, PHP doesn't support passing null as 3rd argument:
        // DateTime::createFromFormat() expects parameter 3 to be DateTimeZone, null given

        if ($timezone === null) {
            $datetime = \DateTime::createFromFormat($format, $time);
        } else {
            $datetime = \DateTime::createFromFormat($format, $time, $timezone);
        }

        if ($datetime === false) {
            throw InvalidTimestamp::create($format, $time);
        }

        return new Timestamp($datetime);
    }

    /**
     * @param \DateTime $datetime
     */
    private function __construct(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }
}
