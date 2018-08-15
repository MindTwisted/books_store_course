<?php

require_once 'app/config/config.php';
require_once 'bootstrap/autoload.php';

use libs\QueryBuilder\src\QueryBuilder;

// Set QueryBuilder instance
$builderMySQL = new QueryBuilder(
    'mysql',
    MYSQL_SETTINGS['host'],
    MYSQL_SETTINGS['port'],
    MYSQL_SETTINGS['database'],
    MYSQL_SETTINGS['user'],
    MYSQL_SETTINGS['password']
);

// Get tables prefix from config
$prefix = TABLE_PREFIX;

// Disable foreign key checks
$builderMySQL->raw("SET FOREIGN_KEY_CHECKS=0");

// Drop tables
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}users");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}books");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}authors");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}genres");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}book_author");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}book_genre");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}payment_types");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}orders");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}order_details");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}cart");
$builderMySQL->raw("DROP TABLE IF EXISTS {$prefix}auth_tokens");

// Enable foreign key checks
$builderMySQL->raw("SET FOREIGN_KEY_CHECKS=1");

// Create users table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}users (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  name VARCHAR(255),
                  email VARCHAR(255),
                  password VARCHAR(255),
                  role ENUM('admin', 'user') DEFAULT 'user',
                  discount DECIMAL(8,2) DEFAULT '0.00',
                  UNIQUE (email)
            )"
);

// Create books table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}books (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  title VARCHAR(255),
                  description TEXT,
                  image_url VARCHAR(255),
                  price DECIMAL(8,2),
                  discount DECIMAL(8,2) DEFAULT '0.00',
                  UNIQUE (title)
            )"
);

// Create authors table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}authors (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  name VARCHAR(255),
                  UNIQUE (name)
            )"
);

// Create genres table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}genres (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  name VARCHAR(255),
                  UNIQUE (name)
            )"
);

// Create book_author table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}book_author (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  book_id INT UNSIGNED,
                  author_id INT UNSIGNED,
                  FOREIGN KEY (book_id)
                        REFERENCES {$prefix}books (id) 
                        ON DELETE CASCADE,
                  FOREIGN KEY (author_id)
                        REFERENCES {$prefix}authors (id) 
                        ON DELETE CASCADE
            )"
);

// Create book_genre table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}book_genre (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  book_id INT UNSIGNED,
                  genre_id INT UNSIGNED,
                  FOREIGN KEY (book_id)
                        REFERENCES {$prefix}books (id) 
                        ON DELETE CASCADE,
                  FOREIGN KEY (genre_id)
                        REFERENCES {$prefix}genres (id) 
                        ON DELETE CASCADE
            )"
);

// Create payment_types table
$builderMySQL->raw(
      "CREATE TABLE {$prefix}payment_types (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255)
              )"
);

// Create orders table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}orders (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  user_id INT UNSIGNED,
                  payment_types_id INT UNSIGNED,
                  status ENUM('in_process', 'done') DEFAULT 'in_process',
                  total_discount DECIMAL(8,2),
                  total_price DECIMAL(8,2),
                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (payment_types_id)
                        REFERENCES {$prefix}payment_types (id) 
                        ON DELETE SET NULL,
                  FOREIGN KEY (user_id)
                        REFERENCES {$prefix}users (id)
                        ON DELETE SET NULL
            )"
);

// Create order_details table
$builderMySQL->raw(
      "CREATE TABLE {$prefix}order_details (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    book_id INT UNSIGNED,
                    order_id INT UNSIGNED,
                    book_title VARCHAR(255),
                    book_count INT UNSIGNED,
                    book_price DECIMAL(8,2),
                    book_discount DECIMAL(8,2) DEFAULT '0.00',
                    FOREIGN KEY (book_id)
                          REFERENCES {$prefix}books (id) 
                          ON DELETE SET NULL,
                    FOREIGN KEY (order_id)
                          REFERENCES {$prefix}orders (id)
                          ON DELETE SET NULL
              )"
);

// Create cart table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}cart (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  book_id INT UNSIGNED,
                  user_id INT UNSIGNED,
                  count INT UNSIGNED,
                  FOREIGN KEY (book_id)
                        REFERENCES {$prefix}books (id) 
                        ON DELETE CASCADE,
                  FOREIGN KEY (user_id)
                        REFERENCES {$prefix}users (id)
                        ON DELETE CASCADE
            )"
);

// Create auth_tokens table
$builderMySQL->raw(
    "CREATE TABLE {$prefix}auth_tokens (
                  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  user_id INT UNSIGNED,
                  token VARCHAR(255),
                  expires_at DATETIME,
                  UNIQUE (token),
                  FOREIGN KEY (user_id)
                        REFERENCES {$prefix}users (id)
                        ON DELETE CASCADE
            )"
);