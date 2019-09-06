<?php

namespace Glavpunkt\GpCreatedShipment;

use Api\Glavpunkt\GlapunktApi;
use Exception;
use Glavpunkt\GpCreatedOrders;
use Glavpunkt\GpCreatedOrders\GpCreatedOrdersStd;
use Glavpunkt\GpCreatedShipment;
use Integration\CommonOrders\CommonOrdersByShipment;
use Integration\CommonShipment;
use Integration\Punkt;
use PDO;

/**
 * Созданная накладная в системе ГП
 *
 * @author SergeChepikov
 */
class GpCreatedShipmentStd implements GpCreatedShipment
{
    private $api;
    private $orders;

    public function __construct(CommonShipment $shipment, PDO $db, GlapunktApi $api, GpCreatedOrders $orders = null)
    {
        $this->orders = $orders ?? new GpCreatedOrdersStd(new CommonOrdersByShipment($shipment, $db));
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
