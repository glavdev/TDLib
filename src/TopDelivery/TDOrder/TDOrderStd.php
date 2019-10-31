<?php

namespace TopDelivery\TDOrder;

use Api\TopDelivery\TopDeliveryApi;
use stdClass;
use TopDelivery\TDOrder;
use Traversable;

/**
 * Заказ в ТопДеливери
 *
 * Объектное представление ответа
 *
 * @author SergeChepikov
 */
class TDOrderStd implements TDOrder
{
    private $id;
    private $api;

    public function __construct(int $id, TopDeliveryApi $api)
    {
        $this->id = $id;
        $this->api = $api;
    }

    /**
     * @link https://docs.topdelivery.ru/pages/soapapi/p/?v=2.0#method-getOrdersInfo
     * @return array
     */
    public function info(): array
    {
        $params = [
            'order' => [
                'orderId' => $this->id
            ]
        ];
        $order = $this->api->doRequest('getOrdersInfo', $params)->ordersInfo;

        return [
            'tdId' => $order->orderInfo->orderIdentity->orderId,
            'tdStatusId' => $order->orderInfo->status->id,
            'tdStatusName' => $order->orderInfo->status->name,
            'serv' => 'выдача',
            'sku' => $order->orderInfo->orderIdentity->webshopNumber,
            'price' => $order->orderInfo->clientFullCost,
            'primerka' => 0,
            'client_delivery_price' => $order->orderInfo->clientDeliveryCost,
            'weight' => ceil((int)$order->orderInfo->deliveryWeight->weight / 1000),
            'barcode' => $order->orderInfo->orderIdentity->barcode,
            'is_prepaid' => '',
            'events' => $order->orderInfo->events,
            'buyer_fio' => $order->orderInfo->clientInfo->fio,
            'buyer_phone' => $order->orderInfo->clientInfo->phone,
            'comment' => $order->orderInfo->clientInfo->comment,
            'dst_punkt_id' => $order->orderInfo->pickupAddress->id, // Идентификатор к системе ТопДеливери
            'items_count' => $order->orderInfo->services->places,
            'partial_giveout_enabled' => $order->orderInfo->services->forChoise,
            'can_open_box' => (int)!$order->orderInfo->services->notOpen,
            'parts' => iterator_to_array($this->parts($order->orderInfo->items))
        ];
    }

    /**
     * Номенклатура заказа
     *
     * @param $itemsFromTD
     * @return Traversable
     */
    private function parts($itemsFromTD): Traversable
    {
        // Мы можем получить только один товар
        // и его нужно преобразовать в массив
        $items = ($itemsFromTD instanceof stdClass)
            ? [$itemsFromTD]
            : $itemsFromTD;

        foreach ($items as $item) {
            yield [
                'id' => $item->itemId,
                'tdStatusId' => $item->status->id,
                'tdStatusName' => $item->status->name,
                'name' => $item->name,
                'clientPrice' => $item->clientPrice,
                'declaredPrice' => $item->declaredPrice,
                'article' => $item->article,
                'weight' => $item->weight,
                'barcode' => '',
                'num' => $item->count
            ];
        }
    }
}
