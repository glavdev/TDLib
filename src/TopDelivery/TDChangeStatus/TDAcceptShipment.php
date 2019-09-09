<?php

namespace TopDelivery\TDChangeStatus;

use Api\TopDelivery\TopDeliveryApi;
use Exception;
use Integration\CommonOrder;
use Integration\CommonShipment;
use TopDelivery\TDChangeStatus;

/**
 * Принятие поставки в системе ТопДеливери
 *
 * @author SergeChepikov
 */
class TDAcceptShipment implements TDChangeStatus
{
    private $shipment;
    private $orders;
    private $api;

    /**
     * @param CommonShipment $shipment поставка, которую необходимо принять
     * @param CommonOrder[] $orders заказы, которые нужно отметить как принятые
     * @param TopDeliveryApi $api
     */
    public function __construct(CommonShipment $shipment, array $orders, TopDeliveryApi $api)
    {
        $this->shipment = $shipment;
        $this->orders = $orders;
        $this->api = $api;
    }

    /**
     * Выполнить изменение статуса
     */
    public function do(): void
    {
        $shipmentInfo = $this->shipment->info();
        // Если отправка имеет статус принятого, тогда ничего менять не нужно
        if (in_array($shipmentInfo['status'], ['accepted', 'partly-accepted'])) {
            return;
        }

        if (count($this->orders) === 0) {
            throw new Exception("Для принятия поставки {$shipmentInfo['id']} не передан список заказов");
        }

        $acceptedOrder = [];
        foreach ($this->orders as $order) {
            $acceptedOrder[] = [
                'orderId' => $order->info()['td_id']
            ];
        }

        $params = [
            'orderIdentity' => $acceptedOrder,
            'shipmentId' => $shipmentInfo['id']
        ];

        $this->api->doRequest('saveScanningResults', $params);
    }
}
