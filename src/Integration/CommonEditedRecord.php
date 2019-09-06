<?php

namespace Integration;

/***
 * Измененная запись в таблице интеграции
 *
 * @author SergeChepikov
 */
interface CommonEditedRecord
{
    /**
     * @param array $params параметры для изменения
     */
    public function edit(array $params): void;
}
