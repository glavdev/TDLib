<?php

namespace TopDelivery;

/**
 * Изменение статуса в системе ТопДеливери
 *
 * @author SergeChepikov
 */
interface TDChangeStatus
{
    /**
     * Выполнить изменение статуса
     */
    public function do(): void;
}
