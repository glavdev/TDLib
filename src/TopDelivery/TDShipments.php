<?php

namespace TopDelivery;

use IteratorAggregate;
use Traversable;

/**
 * Список отправок от ТопДеливери
 *
 * @author SergeChepikov
 */
interface TDShipments extends IteratorAggregate
{
    /**
     * Список отправок
     *
     * @return Traversable|TDShipment[]
     */
    public function getIterator(): Traversable;
}
