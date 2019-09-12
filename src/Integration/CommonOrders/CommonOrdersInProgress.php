<?php

namespace Integration\CommonOrders;

use Integration\CommonOrder;
use Integration\CommonOrder\CommonOrderStd;
use Integration\CommonOrders;
use PDO;
use Traversable;

/**
 * Список заказов в интеграции, находящихся в работе
 *
 * Для данных заказов необходимо обновлять статусы
 *
 * @author SergeChepikov
 */
class CommonOrdersInProgress implements CommonOrders
{
    private $db;

    public function __construct(PDO $db)
    {
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
            SELECT td_id
            FROM orders
            WHERE gp_status NOT IN ('completed', 'partly-completed', 'returned')
        ");

        foreach ($orders as $order) {
            yield new CommonOrderStd($order['td_id'], $this->db);
        }
    }
}
