<?php

require_once './phpunit';

require_once 'app/models/Model.php';
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
use \app\models\UsersModel;
use \libs\QueryBuilder\src\QueryBuilder;



class UsersModelTest extends TestCase
{
    private static $usersModel;
    private static $builder;
    private static $dbPrefix;

    public static function setUpBeforeClass()
    {
        self::$builder = new QueryBuilder(
            'mysql',
            DB_HOST,
            DB_PORT,
            DB_DATABASE,
            DB_USER,
            DB_PASSWORD
        );
        self::$dbPrefix = DB_TABLE_PREFIX;

        Model::setBuilder(self::$builder);
        Model::setDbPrefix(self::$dbPrefix);

        self::$usersModel = new UsersModel();
    }

    public static function tearDownAfterClass()
    {
        self::$builder->table(self::$dbPrefix . 'users')
            ->where(['email', '=', 'smith@example.com'])
            ->orWhere(['email', '=', 'michael@example.com'])
            ->delete()
            ->run();
    }

    public function testGetAllUsers()
    {
        $users = self::$usersModel->getAllUsers();

        $this->assertCount(1, $users);
    }

    public function testAddUser()
    {
        $newUserId = self::$usersModel->addUser(
            'Michael Smith', 
            'smith@example.com', 
            '123456'
        );

        $users = self::$usersModel->getAllUsers();

        $this->assertCount(2, $users);
    }

    public function testGetUserById()
    {
        $users = self::$usersModel->getAllUsers();
        $lastUserId = $users[count($users) - 1]['id'];

        $user = self::$usersModel->getUserById($lastUserId);

        $this->assertEquals('Michael Smith', $user[0]['name']);
        $this->assertEquals('smith@example.com', $user[0]['email']);
    }

    public function testUpdateUser()
    {
        $users = self::$usersModel->getAllUsers();
        $lastUserId = $users[count($users) - 1]['id'];

        self::$usersModel->updateUser(
            $lastUserId, 
            'Michael', 
            'michael@example.com', 
            '111111');

        $user = self::$usersModel->getUserById($lastUserId);

        $this->assertEquals('Michael', $user[0]['name']);
        $this->assertEquals('michael@example.com', $user[0]['email']);
    }
}