<?php

namespace Glavpunkt\GpOrderStatus;

use Api\Glavpunkt\GlapunktApi;
use Exception;
use Glavpunkt\GpOrderStatus;
use Integration\CommonOrder;

/**
 * Статус заказа в системе Главпункт
 *
 * @author SergeChepikov
 */
class GpOrderStatusStd implements GpOrderStatus
{
    private $info;
    private $order;
    private $api;

    public function __construct(CommonOrder $order, GlapunktApi $api)
    {
        $this->order = $order;
        $this->api = $api;
    }

    /**
     * Текущий статус заказа
     *
     * @return string
     */
    public function status(): string
    {
        return $this->detailedStatus()['status'];
    }

    /**
     * Способ оплаты
     *
     * Метод доступен, если статус заказа completed или partly-completed
     *
     * @return string
     */
    public function paymentType(): string
    {
        if (in_array($this->status(), ['completed', 'partly-completed'])) {
            return $this->detailedStatus()['paymentType'];
        } else {
            throw new Exception("Для статуса {$this->status()} заказа {$this->order->info()['sku']} " .
                "невозможно получить способ оплаты. Статус должен быть 'completed' или 'partly-completed'");
        }
    }

    /**
     * Список выданных частей заказа, при частичной выдаче
     *
     * @return array
     */
    public function parts(): array
    {
        if (in_array($this->status(), ['partly-completed'])) {
            return $this->detailedStatus()['parts'];
        } else {
            throw new Exception("Для статуса {$this->status()} заказа {$this->order->info()['sku']} " .
                "невозможно получить список выданных частей. Статус должен быть 'partly-completed'");
        }
    }

    /**
     * Сумма, полученная от покупателя за заказ
     *
     * @return float
     */
    public function price(): float
    {
        if (in_array($this->status(), ['completed', 'partly-completed'])) {
            return $this->detailedStatus()['price'];
        } else {
            throw new Exception("Для статуса {$this->status()} заказа {$this->order->info()['sku']} " .
                "невозможно получить сумму, полученную от покупателя за заказа. " .
                "Статус должен быть 'completed' или 'partly-completed'");
        }
    }

    /**
     * Номер возвратного заказа для частичной выдачи
     *
     * @return string
     */
    public function pkgReturnSku(): string
    {
        if (in_array($this->status(), ['partly-completed'])) {
            return $this->detailedStatus()['pkg-return'];
        } else {
            throw new Exception("Для статуса {$this->status()} заказа {$this->order->info()['sku']} " .
                "невозможно получить номер возвратного заказа. Статус должен быть 'partly-completed'");
        }
    }

    /**
     * Детальный статус заказа, получаемый от системы Главпункт
     *
     * @return array
     */
    private function detailedStatus(): array
    {
        if (!$this->info) {
            $sku = $this->order->info()['sku'];

            $pkgsStatuses = $this->api->getRequest('/api/pkg_status_detailed', ['sku' => [$sku]]);

            if (isset($pkgsStatuses[$sku])) {
                $this->info = $pkgsStatuses[$sku];
            } else {
                throw new Exception("При запросе статуса заказа $sku получен неправильный ответ: " .
                    print_r($pkgsStatuses, true));
            }
        }

        return $this->info;
    }
}
