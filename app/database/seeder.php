<?php

require_once '../bootstrap/env.php';
require_once '../app/config/config.php';
require_once '../bootstrap/autoload.php';
require_once '../bootstrap/start.php';

// Set QueryBuilder instance
$builderMySQL = $queryBuilder;

// Get tables prefix from config
$prefix = DB_TABLE_PREFIX;

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
            ['Stephen King'],
            ['Robert Galbraith']
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
            [
                  'Harry Potter and the Chamber of Secrets',
                  "Harry Potter's summer has included the worst birthday ever, doomy warnings from a house-elf called Dobby, and rescue from the Dursleys by his friend Ron Weasley in a magical flying car! Back at Hogwarts School of Witchcraft and Wizardry for his second year, Harry hears strange whispers echo through empty corridors - and then the attacks start. Students are found as though turned to stone... Dobby's sinister predictions seem to be coming true.",
                  14.69,
                  'storage/images/books/harry-potter-and-the-chamber-of-secrets.jpg'
            ],
            [
                  'Elevation',
                  "Although Scott Carey doesn’t look any different, he’s been steadily losing weight. There are a couple of other odd things, too. He weighs the same in his clothes and out of them, no matter how heavy they are. Scott doesn’t want to be poked and prodded. He mostly just wants someone else to know, and he trusts Doctor Bob Ellis. In the small town of Castle Rock, the setting of many of King’s most iconic stories, Scott is engaged in a low grade—but escalating—battle with the lesbians next door whose dog regularly drops his business on Scott’s lawn. One of the women is friendly; the other, cold as ice. Both are trying to launch a new restaurant, but the people of Castle Rock want no part of a gay married couple, and the place is in trouble. When Scott finally understands the prejudices they face–including his own—he tries to help. Unlikely alliances, the annual foot race, and the mystery of Scott’s affliction bring out the best in people who have indulged the worst in themselves and others.",
                  13.49,
                  'storage/images/books/elevation.jpg'
            ]
      )
      ->insert()
      ->run();

// Seed book_author table
$builderMySQL->table("{$prefix}book_author")
      ->fields(['book_id', 'author_id'])
      ->values(
            [1, 1],
            [1, 8],
            [2, 7]
      )
      ->insert()
      ->run();

// Seed book_author genre
$builderMySQL->table("{$prefix}book_genre")
      ->fields(['book_id', 'genre_id'])
      ->values(
            [1, 1],
            [1, 2],
            [1, 5],
            [2, 6]
      )
      ->insert()
      ->run();