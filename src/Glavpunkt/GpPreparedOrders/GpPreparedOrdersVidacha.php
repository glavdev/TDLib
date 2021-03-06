<?php

namespace Glavpunkt\GpPreparedOrders;

use Glavpunkt\GpPreparedOrders;
use Integration\CommonOrder;
use Integration\CommonOrders;
use Traversable;

/**
 * Заказы на выдачу, подготовленные к вгрузке в Главпункт
 *
 * @author SergeChepikov
 */
class GpPreparedOrdersVidacha implements GpPreparedOrders
{
    private $orders;

    public function __construct(CommonOrders $orders)
    {
        $this->orders = $orders;
    }

    /**
     * Список заказов для вгрузки в Главпункт
     *
     * @return Traversable|array[]
     */
    public function getIterator(): Traversable
    {
        foreach ($this->orders as $order) {
            $pkgInfo = $order->info();
            yield [
                'serv' => "выдача",
                'pvz_id' => $pkgInfo['dst_punkt_id'],
                'sku' => $pkgInfo['sku'],
                // Сумма к получению. Если передан 0, значит заказ предоплачен.
                'price' => $pkgInfo['price'],
                'insurance_val' => $this->insuranceVal($order), // Оценочная (страховая) стоимость заказа
                'weight' => $pkgInfo['weight'], // Общий вес в кг.
                'primerka' => 0,
                'barcode' => $pkgInfo['barcode'],
                'buyer_fio' => $pkgInfo['td_id'] . " " . $pkgInfo['buyer_fio'],
                'buyer_phone' => $pkgInfo['buyer_phone'],
                'comment' => $pkgInfo['comment'],
                'items_count' => $pkgInfo['items_count'],
                // если предоплачено, то запрещаем частичную выдачу
                'partial_giveout_enabled' => ($pkgInfo['price'] == 0 ? 0 : $pkgInfo['partial_giveout_enabled']),
                'can_open_box' => $pkgInfo['can_open_box'],
                'parts' => $this->parts($order), // Номенклатура заказа
            ];
        }
    }

    /**
     * Номенклатура создаваемого заказа
     *
     * @param CommonOrder $order
     * @return array
     */
    private function parts(CommonOrder $order): array
    {
        $orderInfo = $order->info();
        $parts = [];
        foreach ($order->parts() as $part) {
            $insVal = $part['declared_price'] != 0 ? $part['declared_price'] : 0.01;
            $parts[] = [
                'name' => $part['name'] . " " . $part['id'],
                'price' => $part['price'],
                'insurance_val' => $insVal,
                'num' => $part['num'],
                'weight' => $part['weight'] / 1000
            ];
        }
        if ($orderInfo['client_delivery_price'] > 0) {
            $parts[] = [
                'name' => "Стоимость доставки",
                'price' => $orderInfo['client_delivery_price'],
                'insurance_val' => 0
            ];
        }

        return $parts;
    }

    /**
     * Оценочная (страховая) стоимость заказа
     *
     * @param CommonOrder $order
     * @return float
     */
    private function insuranceVal(CommonOrder $order): float
    {
        $insuranceVal = 0;
        foreach ($order->parts() as $part) {
            $insVal = $part['declared_price'] != 0 ? $part['declared_price'] : 0.01;
            $insuranceVal += $insVal * $part['num'];
        }

        return $insuranceVal;
    }
}
