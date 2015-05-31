<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Aggregate\Factory;

use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Exception\IdNotMappedToAggregate;
use SimpleES\EventSourcing\Exception\InvalidTypeInCollection;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class MappingAggregateFactory implements ReconstitutesAggregates
{
    /**
     * @var array
     */
    private $map;

    /**
     * @param array $map
     */
    public function __construct(array $map)
    {
        foreach ($map as $idClass => $aggregateClass) {
            $this->guardIdClass($idClass);
            $this->guardAggregateClass($aggregateClass);

            $this->map[$idClass] = $aggregateClass;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reconstituteFromHistory(AggregateHistory $aggregateHistory)
    {
        $idClass        = get_class($aggregateHistory->aggregateId());
        $aggregateClass = $this->mapIdClassToAggregateClass($idClass);

        /** @noinspection PhpUndefinedMethodInspection */

        return $aggregateClass::fromHistory($aggregateHistory);
    }

    /**
     * @param string $class
     * @return string
     * @throws IdNotMappedToAggregate
     */
    private function mapIdClassToAggregateClass($class)
    {
        if (!isset($this->map[$class])) {
            throw IdNotMappedToAggregate::create($class);
        }

        return $this->map[$class];
    }

    /**
     * @param string $class
     */
    private function guardIdClass($class)
    {
        $this->ensureInstanceOf('SimpleES\EventSourcing\Identifier\Identifies', $class);
    }

    /**
     * @param string $class
     */
    private function guardAggregateClass($class)
    {
        $this->ensureInstanceOf('SimpleES\EventSourcing\Aggregate\TracksEvents', $class);
    }

    /**
     * @param string $expected
     * @param string $actual
     * @throws InvalidTypeInCollection
     */
    private function ensureInstanceOf($expected, $actual)
    {
        if (!is_string($actual) || !is_subclass_of($actual, $expected)) {
            throw InvalidTypeInCollection::create($actual, $expected);
        }
    }
}
