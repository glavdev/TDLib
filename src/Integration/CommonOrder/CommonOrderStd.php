<?php

namespace Integration\CommonOrder;

use Exception;
use Integration\CommonOrder;
use PDO;
use Traversable;

/**
 * Заказ в интеграции, полученный по идентификатору
 *
 * @author SergeChepikov
 */
class CommonOrderStd implements CommonOrder
{
    private $id;
    private $db;

    /**
     * @param int|string $id идентификатор заказа в ГП или в TD
     * @param PDO $db
     */
    public function __construct($id, PDO $db)
    {
        $this->id = $id;
        $this->db = $db;
    }

    /**
     * Информация о заказе
     *
     * @return array
     */
    public function info(): array
    {
        $info = $this->db->query("
            SELECT * 
            FROM `orders` 
            WHERE `sku` = " . $this->db->quote($this->id) . " 
            OR `td_id` = " . $this->db->quote($this->id))->fetch();

        if ($info === false) {
            throw new Exception("Пункт с идентификатором {$this->id} не найден.");
        }

        return $info;
    }

    /**
     * Номенклатура заказа
     *
     * @return Traversable|array[]
     */
    public function parts(): Traversable
    {
        $parts = $this->db->query("
            SELECT *
            FROM `order_part`
            WHERE `order_id` = " . $this->db->quote($this->info()['td_id']));

        foreach ($parts as $part) {
            yield $part;
        }
    }
}
