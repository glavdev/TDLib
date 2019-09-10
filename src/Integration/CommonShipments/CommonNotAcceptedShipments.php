<?php

namespace Integration\CommonShipments;

use Integration\CommonShipment;
use Integration\CommonShipment\CommonShipmentStd;
use Integration\CommonShipments;
use PDO;
use Traversable;

/**
 * Получение списка непринятых поставок
 *
 * @author SergeChepikov
 */
class CommonNotAcceptedShipments implements CommonShipments
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @return Traversable|CommonShipment[]
     */
    public function getIterator(): Traversable
    {
        $pkgs = $this->db->query("
            SELECT * 
            FROM `shipments` 
            WHERE `status` NOT IN ('accepted','partly-accepted')");

        foreach ($pkgs as $pkg) {
            yield new CommonShipmentStd($pkg['id'], $this->db);
        }
    }
}
