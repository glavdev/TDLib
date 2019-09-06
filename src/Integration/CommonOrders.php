<?php

namespace Integration;

use IteratorAggregate;
use Traversable;

/**
 * Список заказов, находящихся в интеграции
 *
 * @author SergeChepikov
 */
interface CommonOrders extends IteratorAggregate
{
    /**
     * Список заказов
     *
     * @return Traversable|CommonOrder[]
     */
    public function getIterator(): Traversable;
}
