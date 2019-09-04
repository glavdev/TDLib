<?php

namespace Api\TopDelivery;

use stdClass;

/**
 * API TopDelivery
 *
 * @author SergeChepikov
 */
interface TopDeliveryApi
{
    /**
     * Выполнение запроса по API
     *
     * @param string $method
     * @param array $params
     * @return stdClass
     */
    public function doRequest(string $method, array $params = []): stdClass;
}
