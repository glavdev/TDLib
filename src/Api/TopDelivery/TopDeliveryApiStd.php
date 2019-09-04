<?php

namespace Api\TopDelivery;

use Exception;
use SoapClient;
use stdClass;

/**
 * API TopDelivery (SOAP Based)
 *
 * @author SergeChepikov
 */
class TopDeliveryApiStd implements TopDeliveryApi
{
    private $login;
    private $password;
    private $soapClient;

    /**
     * @param string $login
     * @param string $password
     * @param SoapClient $soapClient
     * @throws \SoapFault
     */
    public function __construct(
        string $login,
        string $password,
        SoapClient $soapClient
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->soapClient = $soapClient;
    }

    /**
     * Выполнение запроса
     *
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws Exception
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

        logmsg("[REQUEST to $method] " . print_r($params, true), "tdi_api.log");

        $result = $this->soapClient->$method($params);

        logmsg("[ANSWER from $method] " . print_r($result, true), "tdi_api.log");

        if ($result->requestResult->status !== 0) {
            logmsg("[REQUEST to $method] " . print_r($params, true), "tdi_api_error.log");
            logmsg("[ANSWER from $method] " . print_r($result, true), "tdi_api_error.log");
            throw new Exception("Запрос {$method} окончился ошибкой {$result->requestResult->message}. " .
                "Получен ответ: " . print_r($result, true));
        }

        return $result;
    }
}
