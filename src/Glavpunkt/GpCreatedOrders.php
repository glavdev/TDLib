<?php

namespace Glavpunkt;

use IteratorAggregate;
use Traversable;

/**
 * Созданные заказы в Главпункт
 *
 * @author SergeChepikov
 */
interface GpCreatedOrders extends IteratorAggregate
{
    /**
     * Список заказов для вгрузки в Главпункт
     *
     * @return Traversable|array[]
     */
    public function getIterator(): Traversable;
}
