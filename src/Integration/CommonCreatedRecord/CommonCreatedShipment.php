<?php

namespace Integration\CommonCreatedRecord;

use Integration\CommonCreatedRecord;
use Integration\Punkt;
use Integration\Punkt\PunktStd;
use PDO;
use TopDelivery\TDShipment;

/**
 * Новая отправка из ТопДеливери в интеграции
 *
 * @author SergeChepikov
 */
class CommonCreatedShipment implements CommonCreatedRecord
{
    private $shipment;
    private $db;
    private $punkt;

    public function __construct(TDShipment $shipment, PDO $db, Punkt $punkt = null)
    {
        $this->shipment = $shipment;
        $this->db = $db;
        $this->punkt = function ($tdId) use ($punkt, $db) {
            return $punkt ?? new PunktStd($tdId, $db);
        };
    }

    /**
     * Создать новую отправку в интеграции
     *
     * @return int идентификатор созданной отправки
     */
    public function create(): int
    {
        $shipmentInfo = $this->shipment->info();

        $data = [
            'id' => $shipmentInfo['id'],
            'punkt_id' => $this->punkt->call($this, $shipmentInfo['pickupAddressId'])->gpId()
        ];
        $sql = "INSERT INTO `shipments` (`id`, `punkt_id`, `created_date`) VALUES (:id, :punkt_id, NOW())";
        $query = $this->db->prepare($sql);
        $query->execute($data);

        return $shipmentInfo['id'];
    }
}
