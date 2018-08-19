<?php

use libs\Validator;
use libs\Auth;
use libs\View;
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

Auth::setDbPrefix(TABLE_PREFIX);
Auth::setBuilder($queryBuilder);
Auth::setTokenExpiresTime(AUTH_TOKEN_EXPIRES);

View::setRenderType(DEFAULT_VIEW_TYPE);