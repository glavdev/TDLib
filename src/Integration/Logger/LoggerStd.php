<?php

namespace Integration\Logger;

use DateTime;
use Integration\Logger;

/**
 * Логирование
 *
 * @author SergeChepikov
 */
class LoggerStd implements Logger
{
    private $path;

    /**
     * @param string $path путь для логирования
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Заносит сообщение в лог.
     *
     * @param string $message сообщение для логирования
     * @param string $path путь для логирования
     */
    public function log(string $message, string $path = null): void
    {
        $date = (new DateTime)->format('d.m.Y H:i:s');
        $logEntry = $date . ' ' . $message . "\n";
        $logPath = dirname(__DIR__) . '/logs/' . ($path ?? $this->path);
        error_log($logEntry, 3, $logPath);
    }
}
