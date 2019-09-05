<?php

namespace TopDelivery\TDShipment;

use stdClass;
use TopDelivery\TDShipment;

/**
 * Отправка ТопДеливери
 *
 * Объектное представление ответа ТопДеливери
 *
 * @author SergeChepikov
 */
class TDShipmentStd implements TDShipment
{
    private $shipment;

    /**
     * @param stdClass $shipment
     * (
     *      [shipmentId] => 380363 // идентификатор отправки
     *      [weight] => 1
     *      [placesCount] => 1
     *      [ordersCount] => 1
     *      [dateCreate] => 2019-06-03 10:00:35
     *      [sorted] =>
     *      [pickupAddress] => stdClass Object
     *      (
     *          [id] => 761 // код ПВЗ в системе ТопДеливери
     *          [address] =>
     *          [name] =>
     *      )
     *      [status] => stdClass Object
     *      (
     *          [id] => 3 // актуальный статус
     *          [name] => В пути
     *      )
     *      [intakeParams] => stdClass Object
     *      (
     *          [need] => 1
     *          [address] => Нижегородская
     *          [contacts] => 84956603683
     *          [intakeDate] => stdClass Object
     *          (
     *              [date] => 2019-06-05
     *              [timeInterval] => stdClass Object
     *              (
     *                  [bTime] => 09:00:00
     *                  [eTime] => 18:00:00
     *              )
     *          )
     *      )
     *      [shipmentDirection] => stdClass Object
     *      (
     *          [type] => PARTNER_PICKUP
     *          [receiverId] => 361
     *          [senderId] => 95
     *          [directionName] => PARTNER_PICKUP@361
     *      )
     * )
     */
    public function __construct($shipment)
    {
        $this->shipment = $shipment;
    }


    /**
     * Информация об отправке
     *
     * @return array
     * [
     *      'id' => '380363', // идентификатор отправки
     *      'pickupAddressId' => 761, // код ПВЗ в системе ТопДеливери
     *      'status' => [
     *          'id' => 3 // актуальный статус
     *          'name' => В пути
     *      ]
     * ]
     */
    public function info(): array
    {
        return [
            'id' => $this->shipment->shipmentId, // идентификатор отправки
            'pickupAddressId' => $this->shipment->pickupAddress->id, // код ПВЗ в системе ТопДеливери
            'status' => [
                'id' => $this->shipment->status->id, // актуальный статус
                'name' => $this->shipment->status->name
            ]
        ];
    }
}
