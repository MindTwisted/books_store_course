<?php

namespace app\models;

use libs\QueryBuilder\src\QueryBuilder;

class Model
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

    public function getDbPrefix()
    {
        return $this->dbPrefix;
    }
}