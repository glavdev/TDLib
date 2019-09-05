<?php

namespace Integration;

use IteratorAggregate;
use Traversable;

/**
 * Поставки от ТопДеливери, находящиеся в БД интеграции
 *
 * @author SergeChepikov
 */
interface CommonShipments extends IteratorAggregate
{
    /**
     * @return Traversable|CommonShipment[]
     */
    public function getIterator(): Traversable;
}
