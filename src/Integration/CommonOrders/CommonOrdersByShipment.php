<?php

namespace Integration\CommonOrders;

use Integration\CommonOrder;
use Integration\CommonOrder\CommonOrderStd;
use Integration\CommonOrders;
use Integration\CommonShipment;
use PDO;
use Traversable;

/**
 * Список заказов в интеграции, полученных по отправке ТД, в которой они находятся.
 *
 * @author SergeChepikov
 */
class CommonOrdersByShipment implements CommonOrders
{
    private $shipment;
    private $db;

    public function __construct(CommonShipment $shipment, PDO $db)
    {
        $this->shipment = $shipment;
        $this->db = $db;
    }

    /**
     * Список заказов
     *
     * @return Traversable|CommonOrder[]
     */
    public function getIterator(): Traversable
    {
        $orders = $this->db->query("
            SELECT *
            FROM `orders`
            WHERE `shipment_id` = " . $this->db->quote($this->shipment->info()['id']));

        foreach ($orders as $order) {
            yield new CommonOrderStd($order['td_id'], $this->db);
        }
    }
}
