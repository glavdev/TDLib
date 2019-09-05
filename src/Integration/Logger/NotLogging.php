<?php

namespace Integration\Logger;

use Integration\Logger;

/**
 * Не логировать ("заглушка")
 *
 * В случает отсутствия необходимости логировать результаты или ошибки, можно использовать данный класс
 * вместо других реализаций интерфейса Logger.
 *
 * @author SergeChepikov
 */
class NotLogging implements Logger
{
    /**
     * Заносит сообщение в лог.
     *
     * @param string $message сообщение для логирования
     * @param string $path путь для логирования
     */
    public function log(string $message, string $path = null): void
    {
        return;
    }
}
