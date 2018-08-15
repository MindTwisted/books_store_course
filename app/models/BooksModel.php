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
        $books = $this->queryBuilder->table("{$this->dbPrefix}books")
            ->fields(['*'])
            ->select()
            ->run();

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