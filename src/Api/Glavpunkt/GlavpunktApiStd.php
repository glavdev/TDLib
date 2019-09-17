<?php

namespace Api\Glavpunkt;

use Exception;
use Integration\Logger;
use Integration\Logger\LoggerStd;

/**
 * API Главпункта
 *
 * @author SergeChepikov
 */
class GlavpunktApiStd implements GlapunktApi
{
    private $login;
    private $token;
    private $basicUrl;
    private $headers;
    private $logger;

    /**
     * @param string $login логин пользователя
     * @param string $token токен
     * @param string $url адрес отправки запроса (напр. /api/take_pkgs)
     * @param array $headers массив заголовков запроса
     * @param Logger $logger логирование
     */
    public function __construct(string $login, string $token, string $url, array $headers = [], Logger $logger = null)
    {
        $this->login = $login;
        $this->token = $token;
        $this->basicUrl = $url;
        $this->headers = $headers;
        $this->logger = $logger ?? new LoggerStd("gp_api.log");
    }

    /**
     * POST запрос
     *
     * @param string $url
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function postRequest(string $url, array $params): array
    {
        $params = array_merge(
            $params,
            [
                'login' => $this->login,
                'token' => $this->token
            ]
        );
        $requestJson = json_encode($params);

        $headers = array_merge(
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($requestJson)
            ],
            $this->headers
        );

        $url = $this->basicUrl . $url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestJson);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'top-delivery-integration');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $answerJson = curl_exec($curl);
        if ($answerJson === false) {
            throw new Exception("POST запрос вернул ошибку (url=" . $url . ") " .
                "\n запрос: " . print_r($params, true) . "\n" .
                curl_error($curl));
        }
        curl_close($curl);

        $this->logger->log("[POST to $url] REQUEST = " . $requestJson . PHP_EOL . "ANSWER = " . $answerJson);

        $answer = json_decode($answerJson, true);

        if (!is_array($answer)) {
            throw new Exception("Запрос на url=$url вернул ответ в неправильном формате. Ответ: " . $answerJson);
        }

        return $answer;
    }

    /**
     * GET запрос
     *
     * @param string $url
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getRequest(string $url, array $params = []): array
    {
        $params = array_merge(
            $params,
            [
                'login' => $this->login,
                'token' => $this->token
            ]
        );

        $url = $this->basicUrl . $url . "?" . http_build_query($params);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $this->headers,
        ]);
        $answerJson = curl_exec($curl);

        $this->logger->log("[GET to $url] " . $answerJson);

        if ($answerJson === false) {
            throw new Exception("GET запрос вернул ошибку (url=" . $url . ") " . curl_error($curl));
        }
        curl_close($curl);

        $answer = json_decode($answerJson, true);

        if (!is_array($answer)) {
            throw new Exception("Запрос на url=$url вернул ответ в неправильном формате. Ответ: " . $answerJson);
        }

        return $answer;
    }
}
