<?php

namespace Codeception;

use Api\TopDelivery\TopDeliveryApi\TopDeliveryFakeApi;
use Codeception\Test\Unit;
use FunctionalTester;
use Integration\CommonCreatedRecord\CommonCreatedOrder;
use MyPDO;
use TopDelivery\TDOrder\TDOrderStd;

/**
 * Создание заказа
 *
 * @author Klepatskiy N.A.
 */
class CommonCreatedOrderTest extends Unit
{
    /**
     * @var FunctionalTester
     */
    protected $tester;

//    protected function _before()
//    {
//        $this->tester->haveInDatabase("orders", ['td_id' => 15]);
//    }
//
//    protected function _after()
//    {
//        getDB()->exec("DELETE FROM orders WHERE td_id=15;");
//    }

    public function testCreate()
    {
        $pdo = new MyPDO;
        // Создаем заказ
        $pdo->query("SET FOREIGN_KEY_CHECKS=0;
        INSERT INTO `orders`
        (`td_id`, `sku`, `serv`, `gp_status`, `td_status_id`, `td_status_name`, `shipment_id`, `return_shipment_id`,
        `barcode`, `price`, `td_status`, `payment_type`, `create_date`, `modified_date`, `pkg_partial`,
        `client_delivery_price`, `weight`, `buyer_fio`, `buyer_phone`, `buyer_address`, `delivery_date`,
        `comment`, `dst_punkt_id`, `items_count`, `partial_giveout_enabled`, `can_open_box`) VALUES
        (1488, 'TEST-PKG3-01102019-1606',	'выдача',	'transfering',	3,	'Получен в ТД',	308896,	NULL,
        '6*TEST-PKG3-01102019-1606',	2000,	NULL,	NULL,	'2019-10-04 12:08:07',	'2019-10-04 12:52:16',	NULL,
        200,	2,	'Тест Тестов',	'89991112233',	'',	'0000-00-00',	'',	'Avtovo-S75',	'1',	1,	1);");

        $answer =
        [
            'td_id' => 1488,
            'shipment_id' => 3,
            'sku' => 'testCreate',
            'barcode' => 'testCreate',
            'price' => 1500,
            'td_status_id' => 11,
            'td_status_name' => 'testCreate',
            'client_delivery_price' => '14',
            'weight' => 1,
            'buyer_fio' => 'test testov',
            'buyer_phone' => '79502477566',
            'comment' => 'comment',
            'dst_punkt_id' => 'Avtovo-S75',
            'items_count' => 1,
            'partial_giveout_enabled' => 0,
            'can_open_box' => 0
        ];
        $tdOrder = new TDOrderStd(1488, new TopDeliveryFakeApi(['getOrdersInfo' => $answer], ['getOrdersInfo' => $answer]));



        $commonOrderCreate = (new CommonCreatedOrder($tdOrder, 3, $pdo))->create();
        print_r($commonOrderCreate);

        $this->tester->assertEquals("", "");
    }
}
