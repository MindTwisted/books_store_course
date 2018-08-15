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

// Create admin user
$builderMySQL->table("{$prefix}users")
      ->fields(['name', 'email', 'password', 'role'])
      ->values(['John Walker', 'john@example.com', password_hash('secret', PASSWORD_BCRYPT), 'admin'])
      ->insert()
      ->run();

// Seed authors table
$builderMySQL->table("{$prefix}authors")
      ->fields(['name'])
      ->values(
            ['J.K. Rowling'],
            ['Dan Brown'],
            ['George R. R. Martin'],
            ['George Ilian'],
            ['Robert T. Kiyosaki'],
            ['George Orwell'],
            ['Stephen King']
      )
      ->insert()
      ->run();

// Seed genres table
$builderMySQL->table("{$prefix}genres")
      ->fields(['name'])
      ->values(
            ['Action'],
            ['Adventure'],
            ['Business'],
            ['Economics'],
            ['Fantasy'],
            ['Horror']
      )
      ->insert()
      ->run();

// Seed books table
$builderMySQL->table("{$prefix}books")
      ->fields(['title', 'description', 'price', 'image_url'])
      ->values(
            ['Harry Potter and the Chamber of Secrets',
             "Harry Potter's summer has included the worst birthday ever, doomy warnings from a house-elf called Dobby, and rescue from the Dursleys by his friend Ron Weasley in a magical flying car! Back at Hogwarts School of Witchcraft and Wizardry for his second year, Harry hears strange whispers echo through empty corridors - and then the attacks start. Students are found as though turned to stone... Dobby's sinister predictions seem to be coming true.",
             14.69,
             'storage/images/books/harry-potter-and-the-chamber-of-secrets.jpg'
            ]
      )
      ->insert()
      ->run();

// Seed book_author table
$builderMySQL->table("{$prefix}book_author")
      ->fields(['book_id', 'author_id'])
      ->values([1, 1])
      ->insert()
      ->run();

// Seed book_author genre
$builderMySQL->table("{$prefix}book_genre")
      ->fields(['book_id', 'genre_id'])
      ->values(
            [1, 1],
            [1, 2],
            [1, 5]
      )
      ->insert()
      ->run();