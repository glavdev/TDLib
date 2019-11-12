<?php

namespace Codeception;

use Codeception\Test\Unit;
use FunctionalTester;

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

    protected function setUp()
    {
        parent::setUp();
//        getDB()->query("SET FOREIGN_KEY_CHECKS=0;
//        INSERT INTO sotrudniki SET user_id='2416';");
    }

    protected function tearDown()
    {
        parent::tearDown();
//        getDB()->exec("DELETE FROM sotrudniki WHERE user_id=2416;");
    }

    public function testCreate()
    {

    }

}
