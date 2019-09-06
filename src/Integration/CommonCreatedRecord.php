<?php

namespace Integration;

/**
 * Новая запись в БД интеграции
 *
 * @author SergeChepikov
 */
interface CommonCreatedRecord
{
    /**
     * Создать новую запись в БД интеграции
     *
     * @return int идентификатор созданной записи
     */
    public function create(): int;
}
