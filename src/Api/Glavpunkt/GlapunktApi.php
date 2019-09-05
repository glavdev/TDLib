<?php

namespace Api\Glavpunkt;

/**
 * API Главпункт
 *
 * @author SergeChepikov
 */
interface GlapunktApi
{
    /**
     * POST запрос
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function postRequest(string $url, array $params): array;

    /**
     * GET запрос
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    public function getRequest(string $url, array $params = []): array;
}