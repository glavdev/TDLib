<?php

namespace TopDelivery\TDChangeStatus;

use Glavpunkt\GpOrderStatus;
use Integration\CommonEditedStatus;
use TopDelivery\TDChangeStatus;

/**
 * Композер обновления статусов на стороне ТопДеливери
 *
 * @author SergeChepikov
 */
class TDChangeStatusComposed implements TDChangeStatus
{
    private $origs;
    private $orderStatus;
    private $editedStatus;

    /**
     * @param TDChangeStatus[] $origs массив объектов для изменения статусов с ключами в виде статусов заказов
     * @param GpOrderStatus $orderStatus текущий статус заказа в ГП
     * @param CommonEditedStatus $editedStatus
     */
    public function __construct(array $origs, GpOrderStatus $orderStatus, CommonEditedStatus $editedStatus)
    {
        $this->origs = $origs;
        $this->orderStatus = $orderStatus;
        $this->editedStatus = $editedStatus;
    }

    /**
     * Выполнить изменение статуса
     *
     * Если у нас существует, какое-либо действие к этому статусу, его необходимо выполнить.
     * Иначе просто поменять статус заказа в интеграции
     */
    public function do(): void
    {
        if (isset($this->origs[$this->orderStatus->status()])) {
            $this->origs[$this->orderStatus->status()]->do();
        }
        $this->editedStatus->edit($this->orderStatus->status());
    }
}
