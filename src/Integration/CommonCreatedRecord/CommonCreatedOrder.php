<?php

namespace Integration\CommonCreatedRecord;

use Integration\CommonCreatedRecord;
use Integration\Punkt;
use Integration\Punkt\PunktStd;
use PDO;
use TopDelivery\TDOrder;

/**
 * Новый заказ в интеграции
 *
 * @author SergeChepikov
 */
class CommonCreatedOrder implements CommonCreatedRecord
{
    private $order;
    private $db;
    private $punkt;
    private $shipmentId;

    public function __construct(TDOrder $order, int $shipmentId, PDO $db, Punkt $punkt = null)
    {
        $this->order = $order;
        $this->db = $db;
        $this->shipmentId = $shipmentId;
        $this->punkt = function ($id) use ($punkt, $db) {
            return $punkt ?? new PunktStd($id, $db);
        };
    }

    /**
     * Создать новый заказ в интеграции
     *
     * @return int идентификатор созданного заказав
     */
    public function create(): int
    {
        $order = $this->order->info();

        $data = [
            'td_id' => $order['tdId'],
            'shipment_id' => $this->shipmentId,
            'sku' => $order['sku'],
            'barcode' => $order['barcode'],
            'price' => $order['price'],
            'td_status_id' => $order['tdStatusId'],
            'td_status_name' => $order['tdStatusName'],
            'client_delivery_price' => $order['client_delivery_price'],
            'weight' => $order['weight'],
            'buyer_fio' => $order['buyer_fio'],
            'buyer_phone' => $order['buyer_phone'],
            'comment' => $order['comment'],
            'dst_punkt_id' => $this->punkt->call($this, $order['dst_punkt_id'])->gpId(),
            'items_count' => $order['items_count'],
            'partial_giveout_enabled' => $order['partial_giveout_enabled'],
            'can_open_box' => $order['can_open_box']
        ];
        $sql = "INSERT INTO `orders` (`td_id`, `shipment_id`, `sku`, `barcode`, `create_date`, `price`,
            `td_status_id`, `td_status_name`, `client_delivery_price`, `weight`, `buyer_fio`, `buyer_phone`,
            `comment`, `dst_punkt_id`, `items_count`, `partial_giveout_enabled`, `can_open_box`)
            VALUES (:td_id, :shipment_id, :sku, :barcode, NOW(), :price, :td_status_id, :td_status_name,
            :client_delivery_price, :weight, :buyer_fio, :buyer_phone, :comment, :dst_punkt_id, :items_count,
            :partial_giveout_enabled, :can_open_box) 
            ON DUPLICATE KEY UPDATE shipment_id=" . $data["shipment_id"] . ", gp_status=NULL";
        $query = $this->db->prepare($sql);
        $query->execute($data);

        foreach ($order['parts'] as $part) {
            $data = [
                'order_id' => $order['tdId'],
                'id' => $part['id'],
                'td_status_id' => $part['tdStatusId'],
                'td_status_name' => $part['tdStatusName'],
                'name' => $part['name'],
                'price' => $part['clientPrice'], // к оплате клиентом
                'declared_price' => $part['declaredPrice'], // объявленная стоимость
                'barcode' => $part['barcode'],
                'num' => $part['num'],
                'article' => $part['article'],
                'weight' => $part['weight']
            ];
            $sql = "INSERT INTO `order_part` (`order_id`, `id`, `td_status_id`, `td_status_name`, `name`,
                `price`, `barcode`, `num`, `article`, `weight`, `declared_price`)
                VALUES (:order_id, :id, :td_status_id, :td_status_name, :name, :price, :barcode, :num, 
                :article, :weight, :declared_price);";
            $query = $this->db->prepare($sql);
            $query->execute($data);
        }

        return $order['tdId'];
    }
}
