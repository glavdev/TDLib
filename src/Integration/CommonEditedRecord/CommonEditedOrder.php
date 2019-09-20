<?php

namespace Integration\CommonEditedRecord;

use Integration\CommonEditedRecord;
use Integration\CommonOrder;
use PDO;

/**
 * Измененный в интеграции заказ
 *
 * @author SergeChepikov
 */
class CommonEditedOrder implements CommonEditedRecord
{
    private $order;
    private $db;
    private $extraValidFields;

    /**
     * @param CommonOrder $order заказ в интеграции
     * @param PDO $db база данных
     * @param array $extraValidFields дополнительные поля, которые можно изменять в базе у заказа
     */
    public function __construct(CommonOrder $order, PDO $db, array $extraValidFields = [])
    {
        $this->db = $db;
        $this->order = $order;
        $this->extraValidFields = $extraValidFields;
    }

    /**
     * @param array $params параметры для изменения
     */
    public function edit(array $params): void
    {
        $validFields = array_merge([
            'gp_status', 'td_status_id', 'td_status_name', 'account_id',
            'return_shipment_id', 'pkg_partial'
        ],
            $this->extraValidFields
        );
        $sqlSetPart = [];
        foreach ($params as $key => $value) {
            if (in_array($key, $validFields)) {
                $sqlSetPart[] = "`$key` = " . $this->db->quote($value);
            }
        }
        if (count($sqlSetPart) > 0) {
            $this->db->query("
                UPDATE `orders` SET " .
                implode(", ", $sqlSetPart) .
                " WHERE `td_id` = " . $this->db->quote($this->order->info()['td_id']));
        }
    }
}
