<?php

namespace Api\TopDelivery;

use Exception;
use Integration\Logger;
use Integration\Logger\LoggerStd;
use SoapClient;
use stdClass;

/**
 * API TopDelivery (SOAP Based)
 *
 * @link https://docs.topdelivery.ru/pages/soapapi/p/?v=2.0
 * @author SergeChepikov
 */
class TopDeliveryApiStd implements TopDeliveryApi
{
    private $login;
    private $password;
    private $soapClient;
    private $logger;

    /**
     * @param string $login
     * @param string $password
     * @param SoapClient $soapClient
     * @param Logger $logger
     */
    public function __construct(
        string $login,
        string $password,
        SoapClient $soapClient,
        Logger $logger = null
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->soapClient = $soapClient;
        $this->logger = $logger ?? new LoggerStd("tdi_api.log");
    }

    /**
     * Выполнение запроса
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function doRequest(string $method, array $params = []): stdClass
    {
        $auth = [
            'auth' => [
                'login' => $this->login,
                'password' => $this->password
            ]
        ];
        $params = array_merge($auth, $params);

        $this->logger->log("[REQUEST to $method] " . print_r($params, true));

        $result = $this->soapClient->$method($params);

        $this->logger->log("[ANSWER from $method] " . print_r($result, true));

        if ($result->requestResult->status !== 0) {
            $this->logger->log("[REQUEST from $method] " . print_r($params, true), "tdi_api_error.log");
            $this->logger->log("[ANSWER from $method] " . print_r($result, true), "tdi_api_error.log");
            throw new Exception("Запрос {$method} окончился ошибкой {$result->requestResult->message}. " .
                "Получен ответ: " . print_r($result, true));
        }

        return $result;
    }
}
