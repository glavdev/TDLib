<?php

namespace Integration;

use IteratorAggregate;
use Traversable;

/**
 * Поставки, находящиеся в БД интеграции
 *
 * @author SergeChepikov
 */
interface IntegrationShipments extends IteratorAggregate
{
    /**
     * @return Traversable|IntegrationShipment[]
     */
    public function getIterator(): Traversable;
}
