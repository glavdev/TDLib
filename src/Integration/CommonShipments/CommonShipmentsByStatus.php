<?php

namespace Integration\CommonShipments;

use Integration\CommonShipment\CommonShipmentStd;
use Integration\CommonShipments;
use PDO;
use Traversable;

/**
 * Список поставок, находящиеся в БД интеграции, по статусу
 *
 * @author SergeChepikov
 */
class CommonShipmentsByStatus implements CommonShipments
{
    private $status;
    private $db;

    public function __construct(string $status, PDO $db)
    {
        $this->db = $db;
        $this->status = $status;
    }

    /**
     * @return Traversable|CommonShipmentStd[]
     */
    public function getIterator(): Traversable
    {
        $pkgs = $this->db->query("
            SELECT * 
            FROM `shipments` 
            WHERE `status` = " . $this->db->quote($this->status));

        foreach ($pkgs as $pkg) {
            yield new CommonShipmentStd($pkg['id'], $this->db);
        }
    }
}
