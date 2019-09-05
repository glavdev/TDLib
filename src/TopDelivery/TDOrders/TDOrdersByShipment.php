<?php

namespace TopDelivery\TDOrders;

use Api\TopDelivery\TopDeliveryApi;
use TopDelivery\TDOrder;
use TopDelivery\TDOrder\TDOrderStd;
use TopDelivery\TDOrders;
use TopDelivery\TDShipment;
use Traversable;

/**
 * Список заказов находящихся в отправке
 *
 * @author SergeChepikov
 */
class TDOrdersByShipment implements TDOrders
{
    private $api;
    private $shipment;

    public function __construct(TDShipment $shipment, TopDeliveryApi $api)
    {
        $this->api = $api;
        $this->shipment = $shipment;
    }

    /**
     * Список заказов
     *
     * @return Traversable|TDOrder[]
     */
    public function getIterator(): Traversable
    {
        $params = [
            'currentShipment' => $this->shipment->info()['id']
        ];
        $orders = $this->api->doRequest('getOrdersByParams', $params)->orderInfo;
        // Если в списке только один заказ
        if (isset($orders->orderIdentity)) {
            $orders = [$orders];
        }
        foreach ($orders as $order) {
            yield new TDOrderStd($order->orderIdentity->orderId, $this->api);
        }
    }
}
