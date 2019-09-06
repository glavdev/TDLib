<?php

namespace Integration;

/**
 * Измененный статус в интеграции
 *
 * @author SergeChepikov
 */
interface CommonEditedStatus
{
    /**
     * Изменение статуса
     *
     * @param string $status
     */
    public function edit(string $status): void;
}
