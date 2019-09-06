<?php

namespace Integration\Punkt;

use Integration\CommonShipment;
use Integration\Punkt;
use PDO;

/**
 * Пункт получения по отправке
 *
 * @author SergeChepikov
 */
class PunktByShipment implements Punkt
{
    /** @var Punkt */
    private $orig;

    public function __construct(CommonShipment $shipment, PDO $db, Punkt $punkt = null)
    {
        $this->orig = function () use ($shipment, $punkt, $db) {
            return $punkt ?? new PunktStd($shipment->info()['punkt_id'], $db);
        };
    }

    /**
     * Идентификатор пункта в системе TD
     *
     * @return int
     */
    public function tdId(): int
    {
        return $this->orig->call($this)->tdId();
    }

    /**
     * Идентификатор пункта в системе ГП
     *
     * @return string
     */
    public function gpId(): string
    {
        return $this->orig->call($this)->gpId();
    }

    /**
     * Город нахождения пункта
     *
     * @return string
     */
    public function city(): string
    {
        return $this->orig->call($this)->city();
    }
}
