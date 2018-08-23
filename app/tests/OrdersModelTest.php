<?php

require_once './phpunit';

require_once 'app/models/Model.php';
require_once 'app/models/CartModel.php';
require_once 'app/models/OrdersModel.php';
require_once 'app/models/UsersModel.php';

require_once 'libs/QueryBuilder/src/exception/QueryBuilderException.php';
require_once 'libs/QueryBuilder/src/traits/Validators.php';
require_once 'libs/QueryBuilder/src/QueryBuilder.php';
require_once 'libs/Env.php';

use \libs\Env;

Env::setEnvFromFile('./.env');

require_once 'app/config/config.php';

use \PHPUnit\Framework\TestCase;
use \app\models\Model;
use \app\models\CartModel;
use \app\models\OrdersModel;
use \app\models\UsersModel;
use \libs\QueryBuilder\src\QueryBuilder;



class OrdersModelTest extends TestCase
{
    private static $cartModel;
    private static $ordersModel;
    private static $usersModel;

    public static function setUpBeforeClass()
    {
        $queryBuilder = new QueryBuilder(
            'mysql',
            DB_HOST,
            DB_PORT,
            DB_DATABASE,
            DB_USER,
            DB_PASSWORD
        );

        Model::setBuilder($queryBuilder);
        Model::setDbPrefix(DB_TABLE_PREFIX);

        self::$cartModel = new CartModel();
        self::$ordersModel = new OrdersModel();
        self::$usersModel = new UsersModel();
    }

    public function testAddOrder()
    {
        self::$cartModel->addToCart(1, 2, 10);
        self::$cartModel->addToCart(1, 3, 20);

        $cart = self::$cartModel->getUsersCart(1);
        $user = self::$usersModel->getUserById(1);

        self::$ordersModel->addOrder($user[0], $cart, 1);

        $orders = self::$ordersModel->getOrders(null, 1);

        $this->assertCount(1, $orders);
        $this->assertCount(2, $orders[0]['books']);
    }

    public function testUpdateOrder()
    {
        $orders = self::$ordersModel->getOrders(null, 1);
        $orderId = $orders[0]['id'];

        $this->assertEquals('in_process', $orders[0]['status']);

        self::$ordersModel->updateOrder($orderId, 'done');

        $ordersAfterUpdate = self::$ordersModel->getOrders(null, 1);

        $this->assertEquals('done', $ordersAfterUpdate[0]['status']);
    }

    public function testDeleteOrder()
    {
        $orders = self::$ordersModel->getOrders(null, 1);
        $orderId = $orders[0]['id'];

        $this->assertCount(1, $orders);

        self::$ordersModel->deleteOrder($orderId);

        $ordersAfterDelete = self::$ordersModel->getOrders(null, 1);

        $this->assertCount(0, $ordersAfterDelete);
    }
}