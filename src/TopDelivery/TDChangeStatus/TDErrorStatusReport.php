<?php

namespace TopDelivery\TDChangeStatus;

use Glavpunkt\GpOrderStatus;
use TopDelivery\TDChangeStatus;
use TopDelivery\TDOrder;

/**
 * Сообщение об ошибке
 *
 * В случае возникновения рассинхронизации статусов, необходимо уведомить контактные лица в Главпункт и в ТопДеливери
 *
 * @author SergeChepikov
 */
class TDErrorStatusReport implements TDChangeStatus
{
    private $orig;
    private $mailTo;
    private $gpOrderStatus;
    private $tdOrder;

    public function __construct(TDChangeStatus $orig, string $mailTo, GpOrderStatus $gpOrderStatus, TDOrder $tdOrder)
    {
        $this->orig = $orig;
        $this->mailTo = $mailTo;
        $this->gpOrderStatus = $gpOrderStatus;
        $this->tdOrder = $tdOrder;
    }

    /**
     * Выполнить изменение статуса
     */
    public function do(): void
    {
        // @todo #35 описать ситуации, которые могут произойти в результате рассинхрона статусов
        $this->orig->do();
    }
}
