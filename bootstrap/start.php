<?php

use libs\Validator;
use libs\QueryBuilder\src\QueryBuilder;
use app\models\Model;

$queryBuilder = new QueryBuilder(
    'mysql',
    MYSQL_SETTINGS['host'],
    MYSQL_SETTINGS['port'],
    MYSQL_SETTINGS['database'],
    MYSQL_SETTINGS['user'],
    MYSQL_SETTINGS['password']
);

Model::setDbPrefix(TABLE_PREFIX);
Model::setBuilder($queryBuilder);

Validator::setDbPrefix(TABLE_PREFIX);
Validator::setBuilder($queryBuilder);