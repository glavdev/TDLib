<?php

namespace Glavpunkt;

/**
 * Статус заказа в системе Главпункт
 *
 * @author SergeChepikov
 */
interface GpOrderStatus
{
    /**
     * Текущий статус заказа
     *
     * @return string
     */
    public function status(): string;

    /**
     * Способ оплаты
     *
     * Метод доступен, если статус заказа completed или partly-completed
     *
     * @return string
     */
    public function paymentType(): string;

    /**
     * Список выданных частей заказа, при частичной выдаче
     *
     * @return array
     */
    public function parts(): array;

    /**
     * Сумма, полученная от покупателя за заказ
     *
     * @return float
     */
    public function price(): float;

    /**
     * Номер возвратного заказа для частичной выдачи
     *
     * @return string
     */
    public function pkgReturnSku(): string;
}
