<?php

namespace Glavpunkt\GpCreatedOrders;

use Glavpunkt\GpCreatedOrders;
use Integration\CommonOrder;
use Integration\CommonOrders;
use Traversable;

/**
 * Заказы, подготовленные к вгрузке в Главпункт
 *
 * @author SergeChepikov
 */
class GpCreatedOrdersStd implements GpCreatedOrders
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
                'serv' => 'выдача',
                'sku' => $pkgInfo['sku'],
                'price' => $pkgInfo['price'],
                'primerka' => 0,
                'client_delivery_price' => $pkgInfo['client_delivery_price'],
                'weight' => $pkgInfo['weight'],
                'barcode' => $pkgInfo['barcode'],
                'is_prepaid' => ($pkgInfo['price'] == 0),
                'buyer_fio' => $pkgInfo['td_id'] . " " . $pkgInfo['buyer_fio'],
                'buyer_phone' => $pkgInfo['buyer_phone'],
                'comment' => $pkgInfo['comment'],
                'dst_punkt_id' => $pkgInfo['dst_punkt_id'],
                'items_count' => $pkgInfo['items_count'],
                // если предоплачено, то запрещаем частичную выдачу
                'partial_giveout_enabled' => ($pkgInfo['price'] == 0 ? 0 : $pkgInfo['partial_giveout_enabled']),
                'can_open_box' => $pkgInfo['can_open_box'],
                'parts' => $this->parts($order)
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
        $parts = [];
        foreach ($order->parts() as $part) {
            $parts[] = [
                'name' => $part['name'] . " " . $part['id'],
                'price' => $part['price'],
                'num' => $part['num']
            ];
        }

        return $parts;
    }
}
