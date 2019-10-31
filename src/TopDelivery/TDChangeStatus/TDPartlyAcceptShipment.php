<?php

namespace TopDelivery\TDChangeStatus;

use Api\TopDelivery\TopDeliveryApi;
use DateTime;
use Integration\CommonOrder;
use Integration\CommonShipment;
use Integration\Mailer;
use TopDelivery\TDChangeStatus;

/**
 * Частичное принятие поставки от ТопДеливери
 *
 * @author SergeChepikov
 */
class TDPartlyAcceptShipment implements TDChangeStatus
{
    private $accept;
    private $shipment;
    private $acceptedOrders;
    private $orderList;
    private $mail;

    /**
     * @param CommonShipment $shipment поставка в интеграции
     * @param CommonOrder[] $acceptedOrders принятые заказы в поставке с ключом в виде идентификатора
     * @param CommonOrder[] $orderList полный список заказов в поставке
     * @param TopDeliveryApi $api
     * @param Mailer $mail почтовый ящик, куда необходимо отправлять письма о неполных поставках
     * @param TDChangeStatus $accept действие по принятию поставки
     */
    public function __construct(
        CommonShipment $shipment,
        array $acceptedOrders,
        array $orderList,
        TopDeliveryApi $api,
        Mailer $mail,
        TDChangeStatus $accept = null
    ) {
        $this->shipment = $shipment;
        $this->acceptedOrders = $acceptedOrders;
        $this->orderList = $orderList;
        $this->mail = $mail;
        $this->accept = $accept ?? new TDAcceptShipment($shipment, $acceptedOrders, $api);
    }

    /**
     * Выполнить изменение статуса
     */
    public function do(): void
    {
        $shipmentInfo = $this->shipment->info();
        $mailTheme = "Поставка от ТопДеливери {$shipmentInfo['id']} принята не полностью";
        $mailText = (new DateTime())->format("H:i d.m.Y") . "\n" .
            "Поставка от ТопДеливери {$shipmentInfo['id']} принята не полностью. \n" .
            "Идентификатор пункта поступления: {$shipmentInfo['punkt_id']} \n";
        foreach ($this->orderList as $order) {
            $orderInfo = $order->info();
            $mailText .= "Заказ №{$orderInfo['sku']} с идентификатором ТД {$orderInfo['td_id']}: ";
            $mailText .= isset($this->acceptedOrders[$orderInfo['td_id']]) ? "<b>принят</b>\n" : "<b>не принят</b>\n";
        }
        $this->mail->send($mailTheme, $mailText);
        $this->accept->do();
    }
}
