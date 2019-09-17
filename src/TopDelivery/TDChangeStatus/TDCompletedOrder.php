<?php

namespace TopDelivery\TDChangeStatus;

use Api\TopDelivery\TopDeliveryApi;
use Exception;
use Glavpunkt\GpOrderStatus;
use Integration\CommonOrder;
use TopDelivery\TDChangeStatus;

/**
 * Отметка заказа в системе ТопДеливери как полностью выполненного
 *
 * @author SergeChepikov
 */
class TDCompletedOrder implements TDChangeStatus
{
    private $gpOrderStatus;
    private $api;
    private $order;

    public function __construct(CommonOrder $order, GpOrderStatus $gpOrderStatus, TopDeliveryApi $api)
    {
        $this->gpOrderStatus = $gpOrderStatus;
        $this->api = $api;
        $this->order = $order;
    }

    /**
     * Выполнить изменение статуса
     */
    public function do(): void
    {
        $orderInfo = $this->order->info();

        $params = [
            'finalStatusParams' => [
                'orderIdentity' => [
                    'orderId' => $orderInfo['td_id'],
                    'barcode' => $orderInfo['barcode']
                ],
                'shipmentId' => $orderInfo['shipment_id'],
                'accessCode' => md5("{$orderInfo['td_id']}+{$orderInfo['barcode']}"),
                'workStatus' => [
                    'id' => 3,
                    'name' => 'done'
                ],
                'dateFactDelivery' => date("Y-m-d"),
                'clientPaid' => $this->gpOrderStatus->price(),
                'deliveryPaid' => 1,
                'paymentType' => $this->paymentType()
            ]
        ];

        $this->api->doRequest('setOrdersFinalStatus', $params);
    }

    /**
     * Способ оплаты заказа
     *
     * @return string
     */
    private function paymentType(): string
    {
        switch ($this->gpOrderStatus->paymentType()) {
            case "cash":
                return "CASH";
            case "credit":
                return "CARD";
            case "prepaid":
                return "CASH";
            default:
                throw new Exception("В заказе {$this->order->info()['sku']} Способ оплаты  " .
                    $this->gpOrderStatus->paymentType() . " неизвестен.");
        }
    }
}
