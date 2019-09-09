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
            'punkt_id' => $punkt->gpId(),
            'orders' => iterator_to_array($this->orders),
            'comments_client' => ''
        ];
        $result = $this->api->postRequest('/api/take_pkgs', $invoice);

        if ($result['result'] == "ok") {
            return $result['docnum'];
        } else {
            throw new Exception("
                Создание накладной не произошло из-за ошибки API 
                take_pkgs вернул: " . print_r($result, true));
        }
    }
}
