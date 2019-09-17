<?php

namespace TopDelivery\TDChangeStatus;

use Api\TopDelivery\TopDeliveryApi;
use Integration\CommonEditedStatus;
use Integration\CommonEditedStatus\CommonEditedShipmentStatus;
use Integration\CommonShipment;
use TopDelivery\TDChangeStatus;

/**
 * Композер принятия поставки
 *
 * @author SergeChepikov
 */
class TDAcceptShipmentComposed implements TDChangeStatus
{
    private $shipment;
    private $partlyAccept;
    private $accept;
    private $orderList;
    private $acceptedOrders;
    private $editedStatus;

    /**
     * @param CommonShipment $shipment
     * @param array $orderList полный список заказов в поставке
     * @param array $acceptedOrders список принятых заказов в поставке
     * @param TopDeliveryApi $api
     * @param string $mailTo почтовый ящик, куда необходимо отправлять письма о неполных поставках
     * @param CommonEditedStatus $editedStatus измененный статус отправки
     * @param TDChangeStatus $partlyAccept действие для частичного принятия поставки
     * @param TDChangeStatus $accept действие для полного принятия поставки
     */
    public function __construct(
        CommonShipment $shipment,
        array $orderList,
        array $acceptedOrders,
        TopDeliveryApi $api,
        string $mailTo,
        CommonEditedStatus $editedStatus,
        TDChangeStatus $partlyAccept = null,
        TDChangeStatus $accept = null
    ) {
        $this->shipment = $shipment;
        $this->orderList = $orderList;
        $this->acceptedOrders = $acceptedOrders;
        $this->editedStatus = $editedStatus;
        $this->partlyAccept = $partlyAccept
            ?? new TDPartlyAcceptShipment($shipment, $acceptedOrders, $orderList, $api, $mailTo);
        $this->accept = $accept ?? new TDAcceptShipment($shipment, $acceptedOrders, $api);
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

        if (count($this->acceptedOrders) === 0) {
            // не принято ни одного заказа в поставке
            return;
        }

        if (count($this->acceptedOrders) === count($this->orderList)) {
            $this->accept->do();
            $this->editedStatus->edit("accepted");
        } else {
            $this->partlyAccept->do();
            $this->editedStatus->edit("partly-accepted");
        }
    }
}
