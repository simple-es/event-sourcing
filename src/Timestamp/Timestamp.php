<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Timestamp;

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
        return new Timestamp(
            \DateTime::createFromFormat(
                'U.u',
                sprintf('%.6f', microtime(true)),
                new \DateTimeZone('UTC')
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
     * @param \DateTime $datetime
     */
    private function __construct(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }
}
