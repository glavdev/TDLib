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
            SELECT td_id
            FROM orders
            WHERE gp_status NOT IN ('completed', 'returned', 'partly-completed')
        ");

        foreach ($pkgs as $pkg) {
            yield new CommonOrderStd($pkg['td_id'], $this->db);
        }
    }
}
