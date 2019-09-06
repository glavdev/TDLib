<?php

namespace TopDelivery;

use IteratorAggregate;
use Traversable;

/**
 * Список заказов в ТопДеливери
 *
 * @author SergeChepikov
 */
interface TDOrders extends IteratorAggregate
{
    /**
     * Список заказов
     *
     * @return Traversable|TDOrder[]
     */
    public function getIterator(): Traversable;
}
