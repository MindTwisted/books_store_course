<?php

const MYSQL_SETTINGS = [
    'host'     => 'localhost',
    'port'     => 3306,
    'database' => 'user5',
    'user'     => 'user5',
    'password' => 'user5',
];

// const MYSQL_SETTINGS = [
//     'host'     => 'localhost',
//     'port'     => 3306,
//     'database' => 'root',
//     'user'     => 'root',
//     'password' => 'root',
// ];

const ROOT_DIR = '/home/user5/books_store_course';
// const ROOT_DIR = '/var/www/html/books_store_course';

const STORAGE_PATH = '/home/user5/books_store_course/app/storage';
// const STORAGE_PATH = '/var/www/html/books_store_course/app/storage';

const TABLE_PREFIX = 'bs_';
const DEFAULT_VIEW_TYPE = 'json'; // json, html, xml, txt
const AUTH_TOKEN_EXPIRES = 3600; // seconds