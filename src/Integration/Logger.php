<?php

namespace Integration;

/**
 * Логирование
 *
 * @author SergeChepikov
 */
interface Logger
{
    /**
     * Заносит сообщение в лог.
     *
     * @param string $message сообщение для логирования
     * @param string $path путь для логирование ошибок в другой журнал, нежели было задано изначально в конструкторе
     */
    public function log(string $message, string $path = null): void;
}
