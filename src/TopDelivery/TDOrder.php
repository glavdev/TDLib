<?php

namespace TopDelivery;

/**
 * Заказ в системе ТД
 *
 * @author SergeChepikov
 */
interface TDOrder
{
    /**
     * Информация о заказе
     *
     * @return array
     * [
     *  [tdId] => 1944825
     *  [serv] => выдача
     *  [sku] => 219592
     *  [price] => 450
     *  [primerka] => 0
     *  [client_delivery_price] => 160
     *  [weight] => 59
     *  [barcode] => 1116*219592
     *  [is_prepaid] =>
     *  [buyer_fio] => Лариса
     *  [buyer_phone] => 89167766437
     *  [comment] =>
     *  [dst_punkt_id] => 507
     *  [items_count] => 1
     *  [partial_giveout_enabled] => 1
     *  [parts] => [
     *      [0] => [
     *          [name] => Сетевое зарядное устройство для Sony Ericsson Z530i
     *          [price] => 290
     *          [barcode] =>
     *          [num] => 1
     *      ]
     *  ]
     * ]
     */
    public function info(): array;
}
