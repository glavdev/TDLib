<?php

namespace Integration\CommonEditedRecord;

use Integration\CommonEditedRecord;
use Integration\CommonShipment;
use PDO;

/**
 * Измененная в интеграции отправка
 *
 * @author SergeChepikov
 */
class CommonEditedShipment implements CommonEditedRecord
{
    private $shipment;
    private $db;

    public function __construct(CommonShipment $shipment, PDO $db)
    {
        $this->db = $db;
        $this->shipment = $shipment;
    }

    /**
     * @param array $params параметры для изменения
     */
    public function edit(array $params): void
    {
        $validFields = ['move_id', 'status', 'account_id'];
        $sqlSetPart = [];
        foreach ($params as $key => $value) {
            if (in_array($key, $validFields)) {
                $sqlSetPart[] = "`$key` = " . $this->db->quote($value);
            }
        }
        if (count($sqlSetPart) > 0) {
            $this->db->query("
                UPDATE `shipments` SET " .
                implode(", ", $sqlSetPart) .
                " WHERE `id` = " . $this->db->quote($this->shipment->info()['id']));
        }
    }
}
