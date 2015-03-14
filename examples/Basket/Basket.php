<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Basket;

use SimpleES\EventSourcing\Aggregate\EventTrackingCapabilities;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Event\AggregateHistory;
use SimpleES\EventSourcing\Example\Basket\Events\BasketWasPickedUp;
use SimpleES\EventSourcing\Example\Basket\Events\ProductWasAddedToBasket;
use SimpleES\EventSourcing\Example\Basket\Events\ProductWasRemovedFromBasket;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class Basket implements TracksEvents
{
    use EventTrackingCapabilities;

    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var array
     */
    private $products;

    /**
     * @var int
     */
    private $productCount;

    /**
     * {@inheritdoc}
     */
    public static function fromHistory(AggregateHistory $aggregateHistory)
    {
        $basket = new Basket();
        $basket->replayHistory($aggregateHistory);

        return $basket;
    }

    /**
     * @param BasketId $basketId
     * @return Basket
     */
    public static function pickUp(BasketId $basketId)
    {
        $basket = new Basket();
        $basket->recordThat(new BasketWasPickedUp($basketId));

        return $basket;
    }

    /**
     * @param ProductId $productId
     */
    public function addProduct(ProductId $productId)
    {
        $this->guardProductLimit();

        $this->recordThat(new ProductWasAddedToBasket($this->basketId, $productId));
    }

    /**
     * @param ProductId $productId
     */
    public function removeProduct(ProductId $productId)
    {
        if ($this->productNotInBasket($productId)) {
            return;
        }

        $this->recordThat(new ProductWasRemovedFromBasket($this->basketId, $productId));
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->basketId;
    }

    /**
     * @param BasketWasPickedUp $event
     */
    private function whenBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->basketId     = $event->aggregateId();
        $this->products     = [];
        $this->productCount = 0;
    }

    /**
     * @param ProductWasAddedToBasket $event
     */
    private function whenProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $productId = (string) $event->productId();

        if (!isset($this->products[$productId])) {
            $this->products[$productId] = 0;
        }

        $this->products[$productId]++;
        $this->productCount++;
    }

    /**
     * @param ProductWasRemovedFromBasket $event
     */
    private function whenProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        $this->products[(string) $event->productId()]--;
        $this->productCount--;
    }

    /**
     * @throws BasketLimitReached
     */
    private function guardProductLimit()
    {
        if ($this->productCount >= 3) {
            throw BasketLimitReached::create(3);
        }
    }

    /**
     * @param ProductId $productId
     * @return bool
     */
    private function productNotInBasket(ProductId $productId)
    {
        return empty($this->products[(string) $productId]);
    }

    private function __construct()
    {
    }
}
