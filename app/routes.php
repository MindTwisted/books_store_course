<?php

use libs\Router;

Router::add('books.index', [
    'url' => '/api/books',
    'method' => 'GET',
    'controller' => ['app\controllers\BooksController', 'index']
]);

Router::add('books.show', [
    'url' => '/api/books/:id',
    'method' => 'GET',
    'controller' => ['app\controllers\BooksController', 'show']
]);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUrl = '/' . implode('/', array_slice(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), 3));

$matcher = Router::match($requestUrl, $requestMethod);

$controller = $matcher['settings']['controller'][0];
$method = $matcher['settings']['controller'][1];
$param = $matcher['param'];

(new $controller)->$method($param);