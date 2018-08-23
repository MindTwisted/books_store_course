<?php

require_once './phpunit';

require_once 'app/models/Model.php';
require_once 'app/models/BooksModel.php';

require_once 'libs/QueryBuilder/src/exception/QueryBuilderException.php';
require_once 'libs/QueryBuilder/src/traits/Validators.php';
require_once 'libs/QueryBuilder/src/QueryBuilder.php';
require_once 'libs/Env.php';

use \libs\Env;

Env::setEnvFromFile('./.env');

require_once 'app/config/config.php';

use \PHPUnit\Framework\TestCase;
use \app\models\Model;
use \app\models\BooksModel;
use \libs\QueryBuilder\src\QueryBuilder;



class BooksModelTest extends TestCase
{
    private static $booksModel;

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

        self::$booksModel = new BooksModel();
    }

    public function testGetAllBooks()
    {
        $books = self::$booksModel->getAllBooks();
        $book = $books[0];

        $this->assertTrue(count($books) > 0);
        $this->assertArrayHasKey('id', $book);
        $this->assertArrayHasKey('title', $book);
        $this->assertArrayHasKey('description', $book);
        $this->assertArrayHasKey('image_url', $book);
        $this->assertArrayHasKey('price', $book);
        $this->assertArrayHasKey('discount', $book);
        $this->assertArrayHasKey('authors', $book);
        $this->assertArrayHasKey('genres', $book);
    }

    public function testGetBookById()
    {
        $book = self::$booksModel->getBookById(1);

        $this->assertCount(1, $book);

        $book = $book[0];

        $this->assertArrayHasKey('id', $book);
        $this->assertArrayHasKey('title', $book);
        $this->assertArrayHasKey('description', $book);
        $this->assertArrayHasKey('image_url', $book);
        $this->assertArrayHasKey('price', $book);
        $this->assertArrayHasKey('discount', $book);
        $this->assertArrayHasKey('authors', $book);
        $this->assertArrayHasKey('genres', $book);
    }

    public function testAddBook()
    {
        $initialBooks = self::$booksModel->getAllBooks();

        $newBookId = self::$booksModel->addBook(
            'new book title', 
            'new book description', 
            '10', 
            '40'
        );

        $booksWithNewBook = self::$booksModel->getAllBooks();

        $this->assertTrue($newBookId !== null);
        $this->assertTrue(count($initialBooks) + 1 === count($booksWithNewBook));
    }

    public function testUpdateBook()
    {
        $books = self::$booksModel->getAllBooks();
        $lastBookId = $books[count($books) - 1]['id'];

        self::$booksModel->updateBook(
            $lastBookId, 
            'updated title', 
            'description', 
            '10', 
            '10'
        );

        $updatedBook = self::$booksModel->getBookById($lastBookId);

        $this->assertArraySubset(
            [
                'title' => 'updated title',
                'description' => 'description',
                'price' => '10',
                'discount' => '10'
            ], 
            $updatedBook[0]
        );
    }

    public function testDeleteBook()
    {
        $books = self::$booksModel->getAllBooks();
        $lastBookId = $books[count($books) - 1]['id'];

        self::$booksModel->deleteBook($lastBookId);

        $booksAfterDelete = self::$booksModel->getAllBooks();

        $this->assertTrue(count($booksAfterDelete) + 1 === count($books));

        $deletedBook = self::$booksModel->getBookById($lastBookId);

        $this->assertCount(0, $deletedBook);
    }
}