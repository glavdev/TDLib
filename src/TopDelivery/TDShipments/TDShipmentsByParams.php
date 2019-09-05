<?php

namespace TopDelivery\TDShipments;

use Api\TopDelivery\TopDeliveryApi;
use TopDelivery\TDShipment\TDShipmentStd;
use TopDelivery\TDShipments;
use Traversable;

/**
 * Список отправок ТопДеливери по параметрам
 *
 * @author SergeChepikov
 */
class TDShipmentsByParams implements TDShipments
{
    private $api;
    private $params;

    /**
     * @param array $params параметры запроса на получение списка отправок
     * [
     *      'shipmentStatus' => [
     *          'id' => 3
     *      ],
     *      'shipmentDirection' => [
     *          'receiverId' => 361 // идентификатор клиента в TD
     *      ]
     * ]
     * @param TopDeliveryApi $api АПИ по которому необходимо получить список отправок
     */
    public function __construct(array $params, TopDeliveryApi $api)
    {
        $this->params = $params;
        $this->api = $api;
    }

    /**
     * Список отправок
     *
     * @link https://docs.topdelivery.ru/pages/soapapi/p/?v=2.0#complexType-getShipmentsByParams
     * @return Traversable|TDShipmentStd[]
     */
    public function getIterator(): Traversable
    {
        $shipmentList = $this->api->doRequest('getShipmentsByParams', $this->params)->shipments;
        if (is_null($shipmentList)) {
            return;
        }
        // Если в списке только одна поставка
        if (isset($shipmentList->shipmentId)) {
            $shipmentList = [$shipmentList];
        }
        foreach ($shipmentList as $shipment) {
            yield new TDShipmentStd($shipment);
        }
    }
}
