<?php

namespace Integration;

use Traversable;

/**
 * Заказ, находящийся в интеграции
 *
 * @author SergeChepikov
 */
interface CommonOrder
{
    /**
     * Информация о заказе
     *
     * @return array
     */
    public function info(): array;

    /**
     * Номенклатура заказа
     *
     * @return Traversable|array[]
     */
    public function parts(): Traversable;
}
