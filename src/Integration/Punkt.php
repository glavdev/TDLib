<?php

namespace Integration;

/**
 * Пункт выдачи в таблице punkts
 *
 * @author SergeChepikov
 */
interface Punkt
{
    /**
     * Идентификатор пункта в системе TD
     *
     * @return int
     */
    public function tdId(): int;

    /**
     * Идентификатор пункта в системе ГП
     *
     * @return string
     */
    public function gpId(): string;

    /**
     * Город нахождения пункта
     *
     * @return string
     */
    public function city(): string;
}
