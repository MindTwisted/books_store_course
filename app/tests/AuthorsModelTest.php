<?php

require_once './phpunit';

require_once 'app/models/Model.php';
require_once 'app/models/AuthorsModel.php';

require_once 'libs/QueryBuilder/src/exception/QueryBuilderException.php';
require_once 'libs/QueryBuilder/src/traits/Validators.php';
require_once 'libs/QueryBuilder/src/QueryBuilder.php';
require_once 'libs/Env.php';

use \libs\Env;

Env::setEnvFromFile('./.env');

require_once 'app/config/config.php';

use \PHPUnit\Framework\TestCase;
use \app\models\Model;
use \app\models\AuthorsModel;
use \libs\QueryBuilder\src\QueryBuilder;



class AuthorsModelTest extends TestCase
{
    private static $authorsModel;

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

        self::$authorsModel = new AuthorsModel();
    }

    public function testGetAllAuthors()
    {
        $authors = self::$authorsModel->getAllAuthors();
        $author = $authors[0];

        $this->assertArrayHasKey('name', $author);
        $this->assertArrayHasKey('id', $author);
    }

    public function testGetAuthorById()
    {
        $author = self::$authorsModel->getAuthorById(1);

        $this->assertCount(1, $author);
        $this->assertArrayHasKey('name', $author[0]);
        $this->assertArrayHasKey('id', $author[0]);
    }

    public function testAddAuthor()
    {
        $newAuthorId = self::$authorsModel->addAuthor('New Author');
        $newAuthor = self::$authorsModel->getAuthorById($newAuthorId);

        $this->assertEquals(
            ['id' => $newAuthorId, 'name' => 'New Author'], 
            $newAuthor[0]
        );
    }

    public function testUpdateAuthor()
    {
        $authors = self::$authorsModel->getAllAuthors();
        $lastAuthorId = $authors[count($authors) - 1]['id'];

        self::$authorsModel->updateAuthor($lastAuthorId, 'Another Author');
        $updatedAuthor = self::$authorsModel->getAuthorById($lastAuthorId);

        $this->assertEquals(
            ['id' => $lastAuthorId, 'name' => 'Another Author'], 
            $updatedAuthor[0]
        );
    }

    public function testDeleteAuthor()
    {
        $authors = self::$authorsModel->getAllAuthors();
        $lastAuthorId = $authors[count($authors) - 1]['id'];

        self::$authorsModel->deleteAuthor($lastAuthorId);
        $deletedAuthor = self::$authorsModel->getAuthorById($lastAuthorId);

        $this->assertCount(0, $deletedAuthor);
    }
}