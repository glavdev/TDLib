<?php

namespace Glavpunkt;

use IteratorAggregate;
use Traversable;

/**
 * Подготовленные заказы к вгрузке в систему Главпункт
 *
 * @author SergeChepikov
 */
interface GpPreparedOrders extends IteratorAggregate
{
    /**
     * Список заказов для вгрузки в Главпункт
     *
     * @return Traversable|array[]
     */
    public function getIterator(): Traversable;
}
