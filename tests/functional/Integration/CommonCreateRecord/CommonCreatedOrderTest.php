<?php

namespace Codeception;

use Api\TopDelivery\TopDeliveryApi\TopDeliveryFakeApi;
use Codeception\Test\Unit;
use FunctionalTester;
use Integration\CommonCreatedRecord\CommonCreatedOrder;
use Integration\CommonCreatedRecord\CommonCreatedShipment;
use Integration\Punkt\PunktStd;
use TopDelivery\TDOrder\TDOrderStd;
use TopDelivery\TDShipment\TDShipmentStd;

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

    public function testCreate()
    {
        $GLOBALS['config']['dbname'] = 'tdintegration_functional_tests';
        $GLOBALS['config']['dbhost'] = 'dbhost';
        $GLOBALS['config']['username'] = 'tdintegration';
        $GLOBALS['config']['password'] = 'tdintegration';
        $pdo = getDB();

        // Создю пункт
        $pdo->query("SET FOREIGN_KEY_CHECKS=0;
            INSERT INTO `punkts` (`tdId`, `gpId`, `city`) VALUES
            (500,	'Avtovo-S75',	'SPB');");

        // Параметры для отправки API
        $param['order']['orderId'] = 1488;
        // order parts
        $items = (object)[
            'itemId' => 6699698,
            'name' => 'Товар 1',
            'article' => 'RU19OFZ11 - 112',
            'count' => 1,
            'declaredPrice' => 380,
            'clientPrice' => 380,
            'weight' => 1,
            'push' => 1,
            'status' => (object)[
                'id' => 1,
                'name' => 'Не обработан'
            ]
        ];
        // Ожидаемый ответ
        $answer['ordersInfo'] = (object)['orderInfo' => (object)[
            'orderIdentity' => (object)['orderId' => 1488, 'webshopNumber' => 14556, 'barcode' => 'barcode'],
            'status' => (object)['id' => 11, 'name' => 'namestatus'],
            'clientFullCost' => 1500,
            'clientDeliveryCost' => 150,
            'deliveryWeight' => (object)['weight' => 1],
            'clientInfo' => (object)['fio' => 'тест тестов', 'phone' => '79502210575', 'comment' => 'comment'],
            'pickupAddress' => (object)['id' => 'Avtovo-S75'],
            'services' => (object)['places' => 1, 'forChoise' => '0', 'notOpen' => 0],
            'events' => [],
            'items' => $items,
        ]];

        // Отправка
        $shipment = (object) [
            'shipmentId' => 3,
            'pickupAddress' => (object)['id' => 1562],
            'status' => (object)['id' => 1488, 'name' => 'name']
        ];
        $shipment2 = (object) [
            'shipmentId' => 4,
            'pickupAddress' => (object)['id' => 1562],
            'status' => (object)['id' => 1488, 'name' => 'name']
        ];

        $punkt = new PunktStd('Avtovo-S75', $pdo);
        $tdOrder = new TDOrderStd(1488, new TopDeliveryFakeApi(['getOrdersInfo' => $param], ['getOrdersInfo' => $answer]));

        // Создаем отправку
        (new CommonCreatedShipment( new TDShipmentStd($shipment), $pdo, $punkt))->create();
        // Создаем заказ
        $tdId = (new CommonCreatedOrder($tdOrder, 3, $pdo, $punkt))->create();

        // Проверяем, что order.shipment_id = shipment.id 
        $shipId = $this->tester->grabColumnFromDatabase('orders', 'shipment_id', ['td_id' => $tdId])[0];
        $this->tester->assertEquals($shipId, 3);

        // Создаем такой же заказ, но с новой отправкой
        (new CommonCreatedShipment(new TDShipmentStd($shipment2), $pdo, $punkt))->create();
        $tdId = (new CommonCreatedOrder($tdOrder, 4, $pdo, $punkt))->create();

        // Проверяем, чтоб order.shipment_id обновилость на новую поставку
        $shipId = $this->tester->grabColumnFromDatabase('orders', 'shipment_id', ['td_id' => $tdId])[0];
        $this->tester->assertEquals($shipId, 4);
    }
}
