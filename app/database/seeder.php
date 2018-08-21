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
            ],
            [
                  "Harry Potter and the Sorcerer's Stone",
                  "Harry Potter has never even heard of Hogwarts when the letters start dropping on the doormat at number four, Privet Drive. Addressed in green ink on yellowish parchment with a purple seal, they are swiftly confiscated by his grisly aunt and uncle. Then, on Harry's eleventh birthday, a great beetle-eyed giant of a man called Rubeus Hagrid bursts in with some astonishing news: Harry Potter is a wizard, and he has a place at Hogwarts School of Witchcraft and Wizardry. An incredible adventure is about to begin!",
                  14.13,
                  'storage/images/books/harry-potter-and-the-sorcerers-stone.jpeg'
            ],
            [
                  "The Stand",
                  "This is the way the world ends: with a nanosecond of computer error in a Defense Department laboratory and a million casual contacts that form the links in a chain letter of death. And here is the bleak new world of the day after: a world stripped of its institutions and emptied of 99 percent of its people. A world in which a handful of panicky survivors choose sides -- or are chosen. A world in which good rides on the frail shoulders of the 108-year-old Mother Abigail -- and the worst nightmares of evil are embodied in a man with a lethal smile and unspeakable powers: Randall Flagg, the dark man. In 1978 Stephen King published The Stand, the novel that is now considered to be one of his finest works. But as it was first published, The Stand was incomplete, since more than 150,000 words had been cut from the original manuscript. Now Stephen King's apocalyptic vision of a world blasted by plague and embroiled in an elemental struggle between good and evil has been restored to its entirety. The Stand : The Complete And Uncut Edition includes more than five hundred pages of material previously deleted, along with new material that King added as he reworked the manuscript for a new generation. It gives us new characters and endows familiar ones with new depths. It has a new beginning and a new ending. What emerges is a gripping work with the scope and moral comlexity of a true epic. For hundreds of thousands of fans who read The Stand in its original version and wanted more, this new edition is Stephen King's gift. And those who are reading The Stand for the first time will discover a triumphant and eerily plausible work of the imagination that takes on the issues that will determine our survival.",
                  11.22,
                  'storage/images/books/the-stand.jpeg'
            ]
      )
      ->insert()
      ->run();

// Seed book_author table
$builderMySQL->table("{$prefix}book_author")
      ->fields(['book_id', 'author_id'])
      ->values(
            [1, 1],
            [3, 1],
            [2, 7],
            [4, 7]
      )
      ->insert()
      ->run();

// Seed book_genre table
$builderMySQL->table("{$prefix}book_genre")
      ->fields(['book_id', 'genre_id'])
      ->values(
            [1, 1],
            [1, 2],
            [1, 5],
            [3, 1],
            [3, 2],
            [3, 5],
            [2, 6],
            [4, 5],
            [4, 6]
      )
      ->insert()
      ->run();

// Seed payment_types table
$builderMySQL->table("{$prefix}payment_types")
      ->fields(['name'])
      ->values(
            ['PayPal'],
            ['Paymentwall'],
            ['Google Wallet'],
            ['Stripe'],
            ['Skrill'],
            ['Privat24']
      )
      ->insert()
      ->run();