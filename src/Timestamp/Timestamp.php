<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Timestamp;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@nerdsweide.nl>
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
