<?php

namespace Integration\CommonEditedStatus;

use Exception;
use Integration\CommonEditedRecord\CommonEditedShipment;
use Integration\CommonEditedStatus;
use Integration\CommonShipment;
use PDO;

/**
 * Измененный статус отправки в интеграции
 *
 * @author SergeChepikov
 */
class CommonEditedShipmentStatus implements CommonEditedStatus
{
    private $shipment;
    private $editedRecord;
    private $db;

    public function __construct(CommonShipment $shipment, PDO $db)
    {
        $this->editedRecord = new CommonEditedShipment($shipment, $db);
        $this->db = $db;
        $this->shipment = $shipment;
    }

    /**
     * Изменение статуса
     *
     * @param string $status
     */
    public function edit(string $status): void
    {
        $validStatuses = ['none', 'created', 'accepted', 'partly-accepted', 'pre-accepted'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Статус $status не является валидным статусом отправки");
        }
        $this->editedRecord->edit(['status' => $status]);
        $this->db->query("
            INSERT INTO `shipments_log` (`shipment_id`, `status`, `date`)
            VALUES (
                " . $this->db->quote($this->shipment->info()['id']) . ", 
                " . $this->db->quote($status) . ", 
                now()
            );
        ");
    }
}
