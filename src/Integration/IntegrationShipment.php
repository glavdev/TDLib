<?php

namespace Integration;

/**
 * Поставка от ТопДеливери в БД интеграции
 *
 * @author SergeChepikov
 */
interface IntegrationShipment
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
}
