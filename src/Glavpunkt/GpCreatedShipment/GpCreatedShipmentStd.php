<?php

namespace Glavpunkt\GpCreatedShipment;

use Api\Glavpunkt\GlapunktApi;
use Exception;
use Glavpunkt\GpPreparedOrders;
use Glavpunkt\GpCreatedShipment;
use Integration\Punkt;

/**
 * Созданная накладная в системе ГП
 *
 * @author SergeChepikov
 */
class GpCreatedShipmentStd implements GpCreatedShipment
{
    private $api;
    private $orders;

    public function __construct(GpPreparedOrders $orders, GlapunktApi $api)
    {
        $this->orders = $orders;
        $this->api = $api;
    }

    /**
     * Создание накладной
     *
     * @param Punkt $punkt пункт получения отправки
     * @return int идентификатор созданной накладной
     */
    public function create(Punkt $punkt): int
    {
        $invoice = [
            'shipment_options' => [
                'method' => 'self_delivery',
                'punkt_id' => $punkt->gpId(),
                'skip_existed' => '1'
            ],
            'orders' => iterator_to_array($this->orders)
        ];
        $result = $this->api->postRequest('/api/create_shipment', $invoice);

        if ($result['result'] == "ok") {
            return $result['docnum'];
        } else {
            throw new Exception("
                Создание накладной не произошло из-за ошибки API 
                create_shipment вернул: " . print_r($result, true) .
                " При запросе: " . print_r($invoice, true));
        }
    }
}
