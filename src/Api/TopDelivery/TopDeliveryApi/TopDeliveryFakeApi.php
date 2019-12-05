<?php

namespace Api\TopDelivery\TopDeliveryApi;

use Api\TopDelivery\TopDeliveryApi;
use Exception;
use stdClass;

/**
 * Фейковый АПИ Топделивери
 *
 * Возвращает, то что мы просим и проверяет правильность запроса к нему
 *
 * @author Klepatskiy N.A.
 */
class TopDeliveryFakeApi implements TopDeliveryApi
{
    private $params;
    private $answers;

    public function __construct(array $params, array $answers)
    {
        $this->params = $params;
        $this->answers = $answers;
    }

    /**
     * Выполнение запроса по API
     *
     * @param string $method
     * @param array $params
     * @return stdClass
     */
    public function doRequest(string $method, array $params = []): stdClass
    {
        return (object)$this->fakeAnswer($method, $params);
    }

    private function fakeAnswer(string $method, array $params = []): array
    {
        if (!isset($this->answers[$method])) {
            throw new Exception("В TopDeliveryApi не определён вывод для метода $method");
        }

        if (testLibCompareArray($this->params[$method], $params)) {
            return $this->answers[$method];
        }

        throw new Exception("Ошибка сравнения массивов.");
    }
}
