<?php

namespace app\models;

use libs\QueryBuilder\src\QueryBuilder;

class Model
{
    protected static $dbPrefix = '';
    protected static $builder;

    public static function setDbPrefix($prefix)
    {
        self::$dbPrefix = $prefix;
    }

    public static function setBuilder(QueryBuilder $builder)
    {
        self::$builder = $builder;
    }
}