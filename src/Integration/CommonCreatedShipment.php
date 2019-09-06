<?php

namespace Integration;

/**
 * Новая отправка из ТопДеливери в интеграции
 *
 * @author SergeChepikov
 */
interface CommonCreatedShipment
{
    /**
     * Создать новую отправку в интеграции
     *
     * @return int идентификатор созданной отправки
     */
    public function create(): int;
}
