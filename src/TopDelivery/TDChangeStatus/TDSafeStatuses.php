<?php

namespace TopDelivery\TDChangeStatus;

use Glavpunkt\GpOrderStatus;
use Integration\CommonEditedStatus;
use Integration\CommonOrder;
use TopDelivery\TDChangeStatus;

/**
 * Подушка безопасности
 *
 * Простановка дополнительных статусов с префиксом `pre` для безопасноти.
 * необходимо для избежания случайных отметок
 *
 * @author SergeChepikov
 */
class TDSafeStatuses implements TDChangeStatus
{
    private $orig;
    private $order;
    private $gpOrderStatus;
    private $editedStatus;

    /**
     * @param TDChangeStatus $orig
     * @param CommonOrder $order заказ в интеграции
     * @param GpOrderStatus $gpOrderStatus статус заказа в системе Главпункт
     * @param CommonEditedStatus $editedStatus измененный статус заказа в интеграции
     */
    public function __construct(
        TDChangeStatus $orig,
        CommonOrder $order,
        GpOrderStatus $gpOrderStatus,
        CommonEditedStatus $editedStatus
    ) {
        $this->orig = $orig;
        $this->order = $order;
        $this->gpOrderStatus = $gpOrderStatus;
        $this->editedStatus = $editedStatus;
    }

    /**
     * Выполнить изменение статуса
     */
    public function do(): void
    {
        if ($this->order->info()['gp_status'] == $this->gpOrderStatus->status()) {
            // Если заказ уже установлен, дальнейшая работа бесмысленна
            return;
        } elseif ($this->order->info()['gp_status'] !== 'pre-completed' &&
            $this->gpOrderStatus->status() === 'completed'
        ) {
            $this->editedStatus->edit('pre-completed');
        } elseif ($this->order->info()['gp_status'] !== 'pre-partly-completed' &&
            $this->gpOrderStatus->status() === 'partly-completed'
        ) {
            $this->editedStatus->edit('pre-partly-completed');
        } else {
            $this->orig->do();
        }
    }
}
