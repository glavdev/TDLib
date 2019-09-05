<?php

namespace Integration\Punkt;

use Exception;
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

    private function info(): array
    {
        $info = $this->db->query("
            SELECT * 
            FROM `punkts` 
            WHERE `gpId` = " . $this->db->quote($this->id) . " 
            OR `tdId` = " . $this->db->quote($this->id)
        )->fetch();

        if ($info === false) {
            throw new Exception("Пункт с идентификатором {$this->id} не найден.");
        }

        return $info;
    }
}
