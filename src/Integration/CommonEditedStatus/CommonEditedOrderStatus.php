<?php

namespace Integration\CommonEditedStatus;

use Exception;
use Integration\CommonEditedRecord\CommonEditedOrder;
use Integration\CommonEditedStatus;
use Integration\CommonOrder;
use PDO;

/**
 * Измененный статус заказа в интеграции
 *
 * @author SergeChepikov
 */
class CommonEditedOrderStatus implements CommonEditedStatus
{
    private $order;
    private $editedRecord;
    private $db;

    public function __construct(CommonOrder $order, PDO $db)
    {
        $this->editedRecord = new CommonEditedOrder($order, $db);
        $this->db = $db;
        $this->order = $order;
    }

    /**
     * Изменение статуса
     *
     * @param string $status
     */
    public function edit(string $status): void
    {
        $validStatuses = [
            'not found', 'completed', 'awaiting_return', 'partly-completed', 'returned', 'transfering',
            'pre-completed', 'pre-partly-completed', 'waiting', 'none'
        ];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Статус $status не является валидным статусом заказа");
        }
        if ($status !== $this->order->info()['gp_status']) {
            $this->editedRecord->edit(['gp_status' => $status]);
            $this->db->query("
                INSERT INTO `orders_log` (`order_id`, `status`, `date`)
                VALUES (
                    " . $this->db->quote($this->order->info()['td_id']) . ", 
                    " . $this->db->quote($status) . ", 
                    now()
                );
            ");
        }
    }
}
