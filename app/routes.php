<?php

use libs\Router;

// Auth routes
Router::add('auth.index', [
    'url' => '/api/auth',
    'method' => 'GET',
    'controller' => ['app\controllers\AuthController', 'index']
]);

Router::add('auth.store', [
    'url' => '/api/auth',
    'method' => 'POST',
    'controller' => ['app\controllers\AuthController', 'store']
]);

// Books routes
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

Router::add('books.store', [
    'url' => '/api/books',
    'method' => 'POST',
    'controller' => ['app\controllers\BooksController', 'store']
]);

Router::add('books.update', [
    'url' => '/api/books/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\BooksController', 'update']
]);

Router::add('books.delete', [
    'url' => '/api/books/:id',
    'method' => 'DELETE',
    'controller' => ['app\controllers\BooksController', 'delete']
]);

// Authors routes
Router::add('authors.index', [
    'url' => '/api/authors',
    'method' => 'GET',
    'controller' => ['app\controllers\AuthorsController', 'index']
]);

Router::add('authors.show', [
    'url' => '/api/authors/:id',
    'method' => 'GET',
    'controller' => ['app\controllers\AuthorsController', 'show']
]);

Router::add('authors.store', [
    'url' => '/api/authors',
    'method' => 'POST',
    'controller' => ['app\controllers\AuthorsController', 'store']
]);

Router::add('authors.update', [
    'url' => '/api/authors/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\AuthorsController', 'update']
]);

Router::add('authors.delete', [
    'url' => '/api/authors/:id',
    'method' => 'DELETE',
    'controller' => ['app\controllers\AuthorsController', 'delete']
]);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUrl = '/' . implode('/', array_slice(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), 3));

$matcher = Router::match($requestUrl, $requestMethod);

$controller = $matcher['settings']['controller'][0];
$method = $matcher['settings']['controller'][1];
$param = $matcher['param'];

(new $controller)->$method($param);