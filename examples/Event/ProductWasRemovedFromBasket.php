<?php

/**
 * @license https://github.com/simple-es/event-sourcing/blob/master/LICENSE MIT
 */

namespace SimpleES\EventSourcing\Example\Event;

use SimpleES\EventSourcing\Event\SerializableEvent;
use SimpleES\EventSourcing\Example\Basket\BasketId;
use SimpleES\EventSourcing\Example\Product\ProductId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class ProductWasRemovedFromBasket implements SerializableEvent
{
    /**
     * @var BasketId
     */
    private $basketId;

    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @param BasketId  $basketId
     * @param ProductId $productId
     */
    public function __construct(BasketId $basketId, ProductId $productId)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->basketId;
    }

    /**
     * @return ProductId
     */
    public function productId()
    {
        return $this->productId;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'productWasRemovedFromBasket';
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new ProductWasRemovedFromBasket(
            BasketId::fromString($data['basketId']),
            ProductId::fromString($data['productId'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return [
            'basketId'  => (string)$this->basketId,
            'productId' => (string)$this->productId
        ];
    }
}
