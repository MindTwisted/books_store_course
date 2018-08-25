<?php

use libs\Router;

// Auth routes
Router::add('auth.index', [
    'url' => '/api/auth',
    'method' => 'GET',
    'controller' => ['app\controllers\AuthController', 'index'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

Router::add('auth.store', [
    'url' => '/api/auth',
    'method' => 'POST',
    'controller' => ['app\controllers\AuthController', 'store']
]);

Router::add('auth.delete', [
    'url' => '/api/auth',
    'method' => 'DELETE',
    'controller' => ['app\controllers\AuthController', 'delete'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

// Users routes
Router::add('users.index', [
    'url' => '/api/users',
    'method' => 'GET',
    'controller' => ['app\controllers\UsersController', 'index'],
    'filters' => [
        'permission' => 'isAdmin'
    ]
]);

Router::add('users.show', [
    'url' => '/api/users/:id',
    'method' => 'GET',
    'controller' => ['app\controllers\UsersController', 'show'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:users:id'
    ]
]);

Router::add('users.showOrders', [
    'url' => '/api/users/:id/orders',
    'method' => 'GET',
    'controller' => ['app\controllers\UsersController', 'showOrders'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:users:id'
    ]
]);

Router::add('users.store', [
    'url' => '/api/users',
    'method' => 'POST',
    'controller' => ['app\controllers\UsersController', 'store']
]);

Router::add('users.update', [
    'url' => '/api/users/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\UsersController', 'update'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:users:id'
    ]
]);

Router::add('users.updateCurrent', [
    'url' => '/api/users',
    'method' => 'PUT',
    'controller' => ['app\controllers\UsersController', 'updateCurrent'],
    'filters' => [
        'permission' => 'isAuth'
    ]
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
    'controller' => ['app\controllers\BooksController', 'show'],
    'filters' => [
        'paramValidation' => 'exists:books:id'
    ]
]);

Router::add('books.store', [
    'url' => '/api/books',
    'method' => 'POST',
    'controller' => ['app\controllers\BooksController', 'store'],
    'filters' => [
        'permission' => 'isAdmin'
    ]
]);

Router::add('books.storeAuthors', [
    'url' => '/api/books/:id/authors',
    'method' => 'POST',
    'controller' => ['app\controllers\BooksController', 'storeAuthors'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:books:id'
    ]
]);

Router::add('books.storeGenres', [
    'url' => '/api/books/:id/genres',
    'method' => 'POST',
    'controller' => ['app\controllers\BooksController', 'storeGenres'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:books:id'
    ]
]);

Router::add('books.storeImage', [
    'url' => '/api/books/:id/image',
    'method' => 'POST',
    'controller' => ['app\controllers\BooksController', 'storeImage'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:books:id'
    ]
]);

Router::add('books.update', [
    'url' => '/api/books/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\BooksController', 'update'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:books:id'
    ]
]);

Router::add('books.delete', [
    'url' => '/api/books/:id',
    'method' => 'DELETE',
    'controller' => ['app\controllers\BooksController', 'delete'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:books:id'
    ]
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
    'controller' => ['app\controllers\AuthorsController', 'show'],
    'filters' => [
        'paramValidation' => 'exists:authors:id'
    ]
]);

Router::add('authors.showBooks', [
    'url' => '/api/authors/:id/books',
    'method' => 'GET',
    'controller' => ['app\controllers\AuthorsController', 'showBooks'],
    'filters' => [
        'paramValidation' => 'exists:authors:id'
    ]
]);

Router::add('authors.store', [
    'url' => '/api/authors',
    'method' => 'POST',
    'controller' => ['app\controllers\AuthorsController', 'store'],
    'filters' => [
        'permission' => 'isAdmin'
    ]
]);

Router::add('authors.update', [
    'url' => '/api/authors/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\AuthorsController', 'update'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:authors:id'
    ]
]);

Router::add('authors.delete', [
    'url' => '/api/authors/:id',
    'method' => 'DELETE',
    'controller' => ['app\controllers\AuthorsController', 'delete'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:authors:id'
    ]
]);

// Genres routes
Router::add('genres.index', [
    'url' => '/api/genres',
    'method' => 'GET',
    'controller' => ['app\controllers\GenresController', 'index']
]);

Router::add('genres.show', [
    'url' => '/api/genres/:id',
    'method' => 'GET',
    'controller' => ['app\controllers\GenresController', 'show'],
    'filters' => [
        'paramValidation' => 'exists:genres:id'
    ]
]);

Router::add('genres.showBooks', [
    'url' => '/api/genres/:id/books',
    'method' => 'GET',
    'controller' => ['app\controllers\GenresController', 'showBooks'],
    'filters' => [
        'paramValidation' => 'exists:genres:id'
    ]
]);

Router::add('genres.store', [
    'url' => '/api/genres',
    'method' => 'POST',
    'controller' => ['app\controllers\GenresController', 'store'],
    'filters' => [
        'permission' => 'isAdmin'
    ]
]);

Router::add('genres.update', [
    'url' => '/api/genres/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\GenresController', 'update'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:genres:id'
    ]
]);

Router::add('genres.delete', [
    'url' => '/api/genres/:id',
    'method' => 'DELETE',
    'controller' => ['app\controllers\GenresController', 'delete'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:genres:id'
    ]
]);

// Cart routes
Router::add('cart.index', [
    'url' => '/api/cart',
    'method' => 'GET',
    'controller' => ['app\controllers\CartController', 'index'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

Router::add('cart.store', [
    'url' => '/api/cart',
    'method' => 'POST',
    'controller' => ['app\controllers\CartController', 'store'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

Router::add('cart.update', [
    'url' => '/api/cart',
    'method' => 'PUT',
    'controller' => ['app\controllers\CartController', 'update'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

Router::add('cart.delete', [
    'url' => '/api/cart',
    'method' => 'DELETE',
    'controller' => ['app\controllers\CartController', 'delete'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

// Orders routes
Router::add('orders.index', [
    'url' => '/api/orders',
    'method' => 'GET',
    'controller' => ['app\controllers\OrdersController', 'index'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

Router::add('orders.show', [
    'url' => '/api/orders/:id',
    'method' => 'GET',
    'controller' => ['app\controllers\OrdersController', 'show'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:orders:id'
    ]
]);

Router::add('orders.store', [
    'url' => '/api/orders',
    'method' => 'POST',
    'controller' => ['app\controllers\OrdersController', 'store'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

Router::add('orders.update', [
    'url' => '/api/orders/:id',
    'method' => 'PUT',
    'controller' => ['app\controllers\OrdersController', 'update'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:orders:id'
    ]
]);

Router::add('orders.delete', [
    'url' => '/api/orders/:id',
    'method' => 'DELETE',
    'controller' => ['app\controllers\OrdersController', 'delete'],
    'filters' => [
        'permission' => 'isAdmin',
        'paramValidation' => 'exists:orders:id'
    ]
]);

// PaymentTypes routes
Router::add('paymentTypes.index', [
    'url' => '/api/payment-types',
    'method' => 'GET',
    'controller' => ['app\controllers\PaymentTypesController', 'index'],
    'filters' => [
        'permission' => 'isAuth'
    ]
]);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUrl = '/' . implode('/', array_slice(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), 3));

$matcher = Router::match($requestUrl, $requestMethod);

$controller = $matcher['settings']['controller'][0];
$method = $matcher['settings']['controller'][1];
$param = $matcher['param'];

(new $controller)->$method($param);