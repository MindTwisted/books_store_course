<?php

require_once '../bootstrap/env.php';
require_once '../app/config/config.php';
require_once '../app/helpers.php';
require_once '../bootstrap/exception.php';
require_once '../bootstrap/autoload.php';
require_once '../bootstrap/start.php';
require_once '../app/routes.php';

/*
    TODO:
    1) Auth login, logout fix - move logic from controller to Auth class;
    2) Validator fix Laravel style - add fails(), errors() methods;
        Change ::validate to ::make static method and return new instance with errors;
    3) fix BooksModel getAllBooks method - should return all authors/genres for current book when filter enable;
    4) add routes which will filter books with author or genre:
        genres/:id/books
        authors/:id/books
*/