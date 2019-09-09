<?php

namespace TopDelivery\TDChangeStatus;

use Api\TopDelivery\TopDeliveryApi;
use Integration\CommonOrder;
use TopDelivery\TDChangeStatus;

/**
 * Отметка заказа в системе ТопДеливери как полностью выполненного
 *
 * @author SergeChepikov
 */
class TDCompletedOrder implements TDChangeStatus
{
    private $detailedGpStatus;
    private $api;
    private $order;
    private $paymentType;

    public function __construct(CommonOrder $order, array $detailedGpStatus, string $paymentType, TopDeliveryApi $api)
    {
        $this->detailedGpStatus = $detailedGpStatus;
        $this->api = $api;
        $this->order = $order;
        $this->paymentType = $paymentType;
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
                'clientPaid' => $this->detailedGpStatus['price'],
                'deliveryPaid' => 1,
                'paymentType' => $this->paymentType
            ]
        ];

        $this->api->doRequest('setOrdersFinalStatus', $params);
    }
}
