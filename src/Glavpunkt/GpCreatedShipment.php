<?php

namespace Glavpunkt;

use Integration\Punkt;

/**
 * Созданная накладная в системе Главпункт
 *
 * @author SergeChepikov
 */
interface GpCreatedShipment
{
    /**
     * Создание накладной
     *
     * @param Punkt $punkt пункт получения отправки
     * @return int идентификатор созданной накладной
     */
    public function create(Punkt $punkt): int;
}
