<?php

namespace TopDelivery;

/**
 * Отправка ТопДеливери
 *
 * @author SergeChepikov
 */
interface TDShipment
{
    /**
     * Информация об отправке
     *
     * @return array
     * [
     *      'id' => '380363', // идентификатор отправки
     *      'pickupAddressId' => 761, // код ПВЗ в системе ТопДеливери
     *      'status' => [
     *          [id] => 3 // актуальный статус
     *          [name] => В пути
     *      ]
     * ]
     */
    public function info(): array;
}
