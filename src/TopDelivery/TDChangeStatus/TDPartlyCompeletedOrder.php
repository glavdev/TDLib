<?php

namespace TopDelivery\TDChangeStatus;


use Api\TopDelivery\TopDeliveryApi;
use Exception;
use Glavpunkt\GpOrderStatus;
use Integration\CommonOrder;
use TopDelivery\TDChangeStatus;

/**
 * Отметка заказа в системе ТопДеливери как частично выданного
 *
 * @author SergeChepikov
 */
class TDPartlyCompeletedOrder implements TDChangeStatus
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

        $items = [];
        foreach ($this->order->parts() as $part) {
            $count = 0;
            // Просматриваем выданные заказы и ищем текущий
            foreach ($this->gpOrderStatus->parts() as $returnPkg) {
                if ($returnPkg['name'] === $part['name'] . " " . $part['id']) {
                    $count = $returnPkg['count'];
                    break;
                }
            }

            // Если данный заказ выдан, отмечаем сколько данного заказа выдано было
            if ($count) {
                // Если выдано не всё кол-во, то статус должен быть выдано частично
                $status = ($count == $part['num']) ? 3 : 4;
            } else {
                $status = 5;
            }

            $items[] = [
                'itemId' => $part['id'],
                'name' => $part['name'],
                'article' => $part['article'],
                'count' => $part['num'],
                'deliveryCount' => $count,
                'weight' => $part['weight'],
                'push' => '1',
                'clientPrice' => $part['price'],
                'status' => [
                    'id' => $status
                ]
            ];
        }

        $params = [
            'finalStatusParams' => [
                'orderIdentity' => [
                    'orderId' => $orderInfo['td_id'],
                    'barcode' => $orderInfo['barcode']
                ],
                'shipmentId' => $orderInfo['shipment_id'],
                'accessCode' => md5("{$orderInfo['td_id']}+{$orderInfo['barcode']}"),
                'workStatus' => [
                    'id' => 4 // выполнено частично
                ],
                'items' => $items, // состав заказа
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
