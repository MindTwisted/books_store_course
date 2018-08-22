<?php

require_once './phpunit';

require_once 'app/models/Model.php';
require_once 'app/models/GenresModel.php';

require_once 'libs/QueryBuilder/src/exception/QueryBuilderException.php';
require_once 'libs/QueryBuilder/src/traits/Validators.php';
require_once 'libs/QueryBuilder/src/QueryBuilder.php';
require_once 'libs/Env.php';

use \PHPUnit\Framework\TestCase;
use \app\models\Model;
use \app\models\GenresModel;
use \libs\QueryBuilder\src\QueryBuilder;
use \libs\Env;

class GenresModelTest extends TestCase
{
    private static $genresModel;

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

        self::$genresModel = new GenresModel();
    }

    public function testGetAllGenres()
    {
        $genres = self::$genresModel->getAllGenres();

        foreach ($genres as $genre)
        {
            $this->assertArrayHasKey('name', $genre);
            $this->assertArrayHasKey('id', $genre);
        }
    }

    public function testGetGenreById()
    {
        $genre = self::$genresModel->getGenreById(1);

        $this->assertCount(1, $genre);
        $this->assertArrayHasKey('name', $genre[0]);
        $this->assertArrayHasKey('id', $genre[0]);
    }

    public function testAddGenre()
    {
        $newGenreId = self::$genresModel->addGenre('New Genre');
        $newGenre = self::$genresModel->getGenreById($newGenreId);

        $this->assertEquals(
            ['id' => $newGenreId, 'name' => 'New Genre'], 
            $newGenre[0]
        );
    }

    public function testUpdateGenre()
    {
        $genres = self::$genresModel->getAllGenres();
        $lastGenreId = $genres[count($genres) - 1]['id'];

        self::$genresModel->updateGenre($lastGenreId, 'Another Genre');
        $updatedGenre = self::$genresModel->getGenreById($lastGenreId);

        $this->assertEquals(
            ['id' => $lastGenreId, 'name' => 'Another Genre'], 
            $updatedGenre[0]
        );
    }

    public function testDeleteGenre()
    {
        $genres = self::$genresModel->getAllGenres();
        $lastGenreId = $genres[count($genres) - 1]['id'];

        self::$genresModel->deleteGenre($lastGenreId);
        $deletedGenre = self::$genresModel->getGenreById($lastGenreId);

        $this->assertCount(0, $deletedGenre);
    }
}