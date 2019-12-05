<?php

namespace Integration\CommonOrders;

use Integration\CommonOrder;
use Integration\CommonOrder\CommonOrderStd;
use Integration\CommonOrders;
use PDO;
use Traversable;

/**
 * Список заказов, находящихся в работе
 *
 * Список заказов, для которых необходимо обновлять статусы
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
        $pkgs = $this->db->query("
            SELECT td_id, gp_status, td_status_id, td_status_name
            FROM orders
            WHERE (gp_status NOT IN ('completed', 'returned', 'partly-completed', 'awaiting_return')
            OR gp_status IS NULL) AND td_status_id NOT IN (12,20,22,17,19,18,16)
        ");

        foreach ($pkgs as $pkg) {
            yield new CommonOrderStd($pkg['td_id'], $this->db);
        }
    }
}
