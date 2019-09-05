<?php

namespace Integration\IntegrationShipments;

use Integration\IntegrationShipment\IntegrationShipmentStd;
use Integration\IntegrationShipments;
use PDO;
use Traversable;

/**
 * Список поставок в БД интеграции по статусу
 *
 * @author SergeChepikov
 */
class IntegrationShipmentsByStatus implements IntegrationShipments
{
    private $status;
    private $db;

    public function __construct(string $status, PDO $db = null)
    {
        $this->db = $db ?? getDB();
        $this->status = $status;
    }

    /**
     * @return Traversable|IntegrationShipmentStd[]
     */
    public function getIterator(): Traversable
    {
        $pkgs = $this->db->query("
            SELECT * 
            FROM `shipments` 
            WHERE `status` = " . $this->db->quote($this->status) . " 
        ");

        foreach ($pkgs as $pkg) {
            yield new IntegrationShipmentStd($pkg['id']);
        }
    }
}
