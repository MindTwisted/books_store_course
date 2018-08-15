<?php

namespace app\models;

use libs\QueryBuilder\src\QueryBuilder;

class BooksModel
{
    protected $queryBuilder;
    protected $dbPrefix;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder(
            'mysql',
            MYSQL_SETTINGS['host'],
            MYSQL_SETTINGS['port'],
            MYSQL_SETTINGS['database'],
            MYSQL_SETTINGS['user'],
            MYSQL_SETTINGS['password']
        );
        $this->dbPrefix = TABLE_PREFIX;
    }

    public function getAll()
    {
        $books = $this->queryBuilder->raw("
            SELECT
                {$this->dbPrefix}books.id,
                {$this->dbPrefix}books.title,
                {$this->dbPrefix}books.description,
                {$this->dbPrefix}books.image_url,
                {$this->dbPrefix}books.price,
                {$this->dbPrefix}books.discount,
                GROUP_CONCAT(DISTINCT {$this->dbPrefix}authors.name) AS authors,
                GROUP_CONCAT(DISTINCT {$this->dbPrefix}genres.name) AS genres
            FROM {$this->dbPrefix}books
            INNER JOIN {$this->dbPrefix}book_author 
                ON {$this->dbPrefix}books.id = {$this->dbPrefix}book_author.book_id
            INNER JOIN {$this->dbPrefix}authors
                ON {$this->dbPrefix}authors.id = {$this->dbPrefix}book_author.author_id
            INNER JOIN {$this->dbPrefix}book_genre 
                ON {$this->dbPrefix}books.id = {$this->dbPrefix}book_genre.book_id
            INNER JOIN {$this->dbPrefix}genres
                ON {$this->dbPrefix}genres.id = {$this->dbPrefix}book_genre.genre_id
            GROUP BY {$this->dbPrefix}books.id
        ");

        $books = $books->fetchAll(\PDO::FETCH_ASSOC);

        return $books;
    }

    public function getFiltered(array $query)
    {
        $books = $this->queryBuilder->table("{$this->dbPrefix}books")
            ->fields(['*']);

        if (isset($query['author'])
            && isset($query['genre']))
        {
            
        }

        $books = $books->select()->run();

        return $books;
    }
}