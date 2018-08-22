<?php

require_once './phpunit';

require_once 'app/models/Model.php';
require_once 'app/models/AuthorsModel.php';

require_once 'libs/QueryBuilder/src/exception/QueryBuilderException.php';
require_once 'libs/QueryBuilder/src/traits/Validators.php';
require_once 'libs/QueryBuilder/src/QueryBuilder.php';
require_once 'libs/Env.php';

use \PHPUnit\Framework\TestCase;
use \app\models\Model;
use \app\models\AuthorsModel;
use \libs\QueryBuilder\src\QueryBuilder;
use \libs\Env;

class AuthorsModelTest extends TestCase
{
    private static $authorsModel;

    public static function setUpBeforeClass()
    {
        Env::setEnvFromFile('./.env');

        $queryBuilder = new QueryBuilder(
            'mysql',
            Env::get('DB_HOST'),
            Env::get('DB_PORT'),
            Env::get('DB_DATABASE'),
            Env::get('DB_USER'),
            Env::get('DB_PASSWORD')
        );

        Model::setBuilder($queryBuilder);
        Model::setDbPrefix(Env::get('DB_TABLE_PREFIX'));

        self::$authorsModel = new AuthorsModel();
    }

    public function testGetAllAuthors()
    {
        $authors = self::$authorsModel->getAllAuthors();

        foreach ($authors as $author)
        {
            $this->assertArrayHasKey('name', $author);
            $this->assertArrayHasKey('id', $author);
        }
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