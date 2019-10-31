<?php

namespace Integration;

/**
 * Отправка сообщений на email
 *
 * @package Integration
 */
interface Mailer
{
    /**
     * @param string $theme Тема сообщения
     * @param string $message Текст сообщения
     * @param array $emails Дополнительные адреса доставки (кроме указанных в конструкторе)
     */
    public function send(string $theme, string $message, array $emails = []): void;
}
