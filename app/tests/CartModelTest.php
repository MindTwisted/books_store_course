<?php

require_once './phpunit';

require_once 'app/models/Model.php';
require_once 'app/models/CartModel.php';

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
use \libs\QueryBuilder\src\QueryBuilder;



class CartModelTest extends TestCase
{
    private static $cartModel;

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
    }

    public function testAddToCart()
    {
        self::$cartModel->addToCart(1, 2, 10);
        self::$cartModel->addToCart(1, 3, 20);

        $cart = self::$cartModel->getUsersCart(1);

        $this->assertCount(2, $cart);
        $this->assertEquals(10, $cart[0]['count']);
        $this->assertEquals(20, $cart[1]['count']);
    }

    public function testUpdateInCart()
    {
        self::$cartModel->updateInCart(1, 2, 20);

        $cart = self::$cartModel->getUsersCart(1);

        $this->assertCount(2, $cart);
        $this->assertEquals(20, $cart[0]['count']);
        $this->assertEquals(20, $cart[1]['count']);
    }

    public function testDeleteFromCart()
    {
        self::$cartModel->deleteFromCart(1, 2);
        self::$cartModel->deleteFromCart(1, 3);

        $cart = self::$cartModel->getUsersCart(1);

        $this->assertCount(0, $cart);
    }
}