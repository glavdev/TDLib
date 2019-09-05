<?php

namespace Integration\IntegrationShipment;

use Exception;
use Integration\IntegrationShipment;
use PDO;

/**
 * Поставка от ТопДеливери, находящаяся в БД интеграции
 *
 * @author SergeChepikov
 */
class IntegrationShipmentStd implements IntegrationShipment
{
    private $id;
    private $db;

    /**
     * @param int $id
     * @param PDO $db
     */
    public function __construct(int $id, PDO $db = null)
    {
        $this->id = $id;
        $this->db = $db ?? getDB();
    }

    /**
     * Информация о поставке
     *
     * @return array
     *  [
     *      'id' => '308789', // Идентификатор отправки в ТД
     *      'punkt_id' => 'Sklad-SPB', // Пункт, где принимается отправка
     *      'move_id' => '1085760', // Номер накладной в ГП
     *      'status' => 'none', // Текущий статус отправки
     *  ]
     */
    public function info(): array
    {
        $info = $this->db->query("
            SELECT * 
            FROM `shipments` 
            WHERE `id` = " . $this->db->quote($this->id))->fetch();

        if ($info === false) {
            throw new Exception("Отправка с идентификатором {$this->id} не найдена.");
        }

        return $info;
    }
}
