<?php

namespace Integration;

use Traversable;

/**
 * Поставка от ТопДеливери, находящаяся в БД интеграции
 *
 * @author SergeChepikov
 */
interface CommonShipment
{
    /**
     * Информация о поставке
     *
     * @return array
     *  [
     *      'id' => '308789', // Идентификатор отправки в ТД
     *      'punkt_id' => 'Sklad-SPB', // Пункт, где принимается отправка
     *      'move_id' => '1085760', // Номер накладной в ГП
     *      'status' => 'none', // Текущий статус отправки
     *  ]
     */
    public function info(): array;

    /**
     * Список заказов в поставке
     *
     * @return Traversable|CommonOrder[]
     */
    public function orders(): Traversable;
}
