<?php

namespace TopDelivery\TDShipments;

use Integration\CommonShipment\CommonShipmentStd;
use PDO;
use Throwable;
use TopDelivery\TDShipment;
use TopDelivery\TDShipments;
use Traversable;

/**
 * Новые поставки от ТопДеливери
 *
 * Поставки, которые ещё не фигурировали в системе
 *
 * @author SergeChepikov
 */
class TDNewShipments implements TDShipments
{
    private $shipments;
    private $db;

    public function __construct(TDShipments $shipments, PDO $db)
    {
        $this->shipments = $shipments;
        $this->db = $db;
    }

    /**
     * Список новых отправок
     *
     * @return Traversable|TDShipment[]
     */
    public function getIterator(): Traversable
    {
        foreach ($this->shipments as $shipment) {
            try {
                (new CommonShipmentStd($shipment->info()['id'], $this->db))->info();
            } catch (Throwable $t) {
                yield $shipment;
            }
        }
    }
}
