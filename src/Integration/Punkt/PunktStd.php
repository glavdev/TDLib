<?php

namespace Integration\Punkt;

use Exception;
use Integration\Punkt;
use PDO;

/**
 * Пункт выдачи в таблице punkts
 *
 * Можем передавать как идентификатор ГП, так и идентификатор TD
 *
 * @author SergeChepikov
 */
class PunktStd implements Punkt
{
    private $id;
    private $db;
    private $info;

    /**
     * @param int|string $id идентификатор пункта в ГП или в TD
     * @param PDO $db
     */
    public function __construct($id, PDO $db)
    {
        $this->id = $id;
        $this->db = $db;
    }

    public function city(): string
    {
        return $this->info()['city'];
    }

    public function gpId(): string
    {
        return $this->info()['gpId'];
    }

    public function tdId(): int
    {
        return $this->info()['tdId'];
    }

    /**
     * Включён пункт или нет
     *
     * @return bool
     */
    public function enabled(): bool
    {
        return (bool)$this->info()['enabled'];
    }

    private function info(): array
    {
        if (!$this->info) {
            $this->info = $this->db->query("
                SELECT * 
                FROM `punkts` 
                WHERE `gpId` LIKE " . $this->db->quote($this->id) . " 
                OR `tdId` LIKE " . $this->db->quote($this->id)
            )->fetch();

            if ($this->info === false) {
                throw new Exception("Пункт с идентификатором {$this->id} не найден.");
            }
        }

        return $this->info;
    }
}
