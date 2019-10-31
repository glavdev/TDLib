<?php

namespace Integration\Mailer;

use Exception;
use Integration\Mailer;

/**
 * Отправка сообщения на email
 *
 * @author SergeChepikov
 */
class MailerStd implements Mailer
{
    private $emails;

    public function __construct(array $emails = [])
    {
        $this->emails = $emails;
    }

    /**
     * @param string $theme Тема сообщения
     * @param string $message Текст сообщения
     * @param array $emails Дополнительные адреса доставки (кроме указанных в конструкторе)
     */
    public function send(string $theme, string $message, array $emails = []): void
    {
        $emails = array_merge($this->emails, $emails);

        if (empty($emails)) {
            throw new Exception("Для отправки сообщений не указано ни одного адреса");
        }

        mail(implode(",", $emails), $theme, $message);
    }
}
