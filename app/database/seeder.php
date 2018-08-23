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
      ->fields(['title', 'description', 'price', 'discount', 'image_url'])
      ->values(
            [
                  'Harry Potter and the Chamber of Secrets',
                  "Harry Potter's summer has included the worst birthday ever, doomy warnings from a house-elf called Dobby, and rescue from the Dursleys by his friend Ron Weasley in a magical flying car! Back at Hogwarts School of Witchcraft and Wizardry for his second year, Harry hears strange whispers echo through empty corridors - and then the attacks start. Students are found as though turned to stone... Dobby's sinister predictions seem to be coming true.",
                  14.69,
                  0,
                  'storage/images/books/harry-potter-and-the-chamber-of-secrets.jpg'
            ],
            [
                  'Elevation',
                  "Although Scott Carey doesn’t look any different, he’s been steadily losing weight. There are a couple of other odd things, too. He weighs the same in his clothes and out of them, no matter how heavy they are. Scott doesn’t want to be poked and prodded. He mostly just wants someone else to know, and he trusts Doctor Bob Ellis. In the small town of Castle Rock, the setting of many of King’s most iconic stories, Scott is engaged in a low grade—but escalating—battle with the lesbians next door whose dog regularly drops his business on Scott’s lawn. One of the women is friendly; the other, cold as ice. Both are trying to launch a new restaurant, but the people of Castle Rock want no part of a gay married couple, and the place is in trouble. When Scott finally understands the prejudices they face–including his own—he tries to help. Unlikely alliances, the annual foot race, and the mystery of Scott’s affliction bring out the best in people who have indulged the worst in themselves and others.",
                  13.49,
                  10,
                  'storage/images/books/elevation.jpg'
            ],
            [
                  "Harry Potter and the Sorcerer's Stone",
                  "Harry Potter has never even heard of Hogwarts when the letters start dropping on the doormat at number four, Privet Drive. Addressed in green ink on yellowish parchment with a purple seal, they are swiftly confiscated by his grisly aunt and uncle. Then, on Harry's eleventh birthday, a great beetle-eyed giant of a man called Rubeus Hagrid bursts in with some astonishing news: Harry Potter is a wizard, and he has a place at Hogwarts School of Witchcraft and Wizardry. An incredible adventure is about to begin!",
                  14.13,
                  5,
                  'storage/images/books/harry-potter-and-the-sorcerers-stone.jpeg'
            ],
            [
                  "The Stand",
                  "This is the way the world ends: with a nanosecond of computer error in a Defense Department laboratory and a million casual contacts that form the links in a chain letter of death. And here is the bleak new world of the day after: a world stripped of its institutions and emptied of 99 percent of its people. A world in which a handful of panicky survivors choose sides -- or are chosen. A world in which good rides on the frail shoulders of the 108-year-old Mother Abigail -- and the worst nightmares of evil are embodied in a man with a lethal smile and unspeakable powers: Randall Flagg, the dark man. In 1978 Stephen King published The Stand, the novel that is now considered to be one of his finest works. But as it was first published, The Stand was incomplete, since more than 150,000 words had been cut from the original manuscript. Now Stephen King's apocalyptic vision of a world blasted by plague and embroiled in an elemental struggle between good and evil has been restored to its entirety. The Stand : The Complete And Uncut Edition includes more than five hundred pages of material previously deleted, along with new material that King added as he reworked the manuscript for a new generation. It gives us new characters and endows familiar ones with new depths. It has a new beginning and a new ending. What emerges is a gripping work with the scope and moral comlexity of a true epic. For hundreds of thousands of fans who read The Stand in its original version and wanted more, this new edition is Stephen King's gift. And those who are reading The Stand for the first time will discover a triumphant and eerily plausible work of the imagination that takes on the issues that will determine our survival.",
                  11.22,
                  15,
                  'storage/images/books/the-stand.jpeg'
            ],
            [
                  "Origin",
                  "Robert Langdon, Harvard professor of symbology, arrives at the ultramodern Guggenheim Museum Bilbao to attend the unveiling of a discovery that “will change the face of science forever.” The evening’s host is Edmond Kirsch, a forty-year-old billionaire and futurist, and one of Langdon’s first students. But the meticulously orchestrated evening suddenly erupts into chaos, and Kirsch’s precious discovery teeters on the brink of being lost forever. Facing an imminent threat, Langdon is forced to flee. With him is Ambra Vidal, the elegant museum director who worked with Kirsch. They travel to Barcelona on a perilous quest to locate a cryptic password that will unlock Kirsch’s secret. Navigating the dark corridors of hidden history and extreme re­ligion, Langdon and Vidal must evade an enemy whose all-knowing power seems to emanate from Spain’s Royal Palace. They uncover clues that ultimately bring them face-to-face with Kirsch’s shocking discovery…and the breathtaking truth that has long eluded us.",
                  16.94,
                  0,
                  'storage/images/books/origin.jpg'
            ],
            [
                  "50 Life and Business Lessons from Steve Jobs",
                  "Do you want to know what made Steve Jobs, so successful and innovative? This book offers an introduction to Jobs, his business success while building the most valuable company in the world and the lessons that we can learn from him. It is not a text book nor a biography, but more of a cheat sheet for reading on the bus or in the bathroom, so that you can pick out the most significant points without having to carry around a bag of weighty tomes. You can read it all in one sitting, or look up specific case studies as and when you are looking for inspiration or direction. The 50 lessons outlined here are drawn from interviews Jobs has given, from the numerous blogs and books written about him, and, most importantly, from the successes and failures on his road to the Building the greatest company and products in the world.",
                  7.97,
                  20,
                  'storage/images/books/50-life-and-business-lessons-from-steve-jobs.jpeg'
            ],
            [
                  "Fire And Blood",
                  "The thrilling history of the Targaryens comes to life in this masterly work by the author of A Song of Ice and Fire, the inspiration for HBO’s Game of Thrones. With all the fire and fury fans have come to expect from internationally bestselling author George R. R. Martin, this is the first volume of the definitive two-part history of the Targaryens in Westeros. Centuries before the events of A Game of Thrones, House Targaryen—the only family of dragonlords to survive the Doom of Valyria—took up residence on Dragonstone. Fire & Blood begins their tale with the legendary Aegon the Conqueror, creator of the Iron Throne, and goes on to recount the generations of Targaryens who fought to hold that iconic seat, all the way up to the civil war that nearly tore their dynasty apart. What really happened during the Dance of the Dragons? Why was it so deadly to visit Valyria after the Doom? What were Maegor the Cruel’s worst crimes? What was it like in Westeros when dragons ruled the skies? These are but a few of the questions answered in this essential chronicle, as related by a learned maester of the Citadel and featuring more than eighty all-new black-and-white illustrations by artist Doug Wheatley. Readers have glimpsed small parts of this narrative in such volumes as The World of Ice & Fire, but now, for the first time, the full tapestry of Targaryen history is revealed.",
                  20.99,
                  10,
                  "storage/images/books/fire-and-blood.jpeg"
            ],
            [
                  "1984",
                  "Written in 1948, 1984 was George Orwell’s chilling prophecy about the future. And while 1984 has come and gone, his dystopian vision of a government that will do anything to control the narrative is timelier than ever... 'The Party told you to reject the evidence of your eyes and ears. It was their final, most essential command.' Winston Smith toes the Party line, rewriting history to satisfy the demands of the Ministry of Truth. With each lie he writes, Winston grows to hate the Party that seeks power for its own sake and persecutes those who dare to commit thoughtcrimes. But as he starts to think for himself, Winston can’t escape the fact that Big Brother is always watching... A startling and haunting vision of the world, 1984 is so powerful that it is completely convincing from start to finish. No one can deny the influence of this novel, its hold on the imaginations of multiple generations of readers, or the resiliency of its admonitions—a legacy that seems only to grow with the passage of time.",
                  13.49,
                  10,
                  "storage/images/books/1984.jpg"
            ],
            [
                  "A Clash of Kings",
                  "A comet the color of blood and flame cuts across the sky. And from the ancient citadel of Dragonstone to the forbidding shores of Winterfell, chaos reigns. Six factions struggle for control of a divided land and the Iron Throne of the Seven Kingdoms, preparing to stake their claims through tempest, turmoil, and war. It is a tale in which brother plots against brother and the dead rise to walk in the night. Here a princess masquerades as an orphan boy; a knight of the mind prepares a poison for a treacherous sorceress; and wild men descend from the Mountains of the Moon to ravage the countryside. Against a backdrop of incest and fratricide, alchemy and murder, victory may go to the men and women possessed of the coldest steel...and the coldest hearts. For when kings clash, the whole land trembles.",
                  25.72,
                  15,
                  "storage/images/books/a-clash-of-kings.jpeg"
            ],
            [
                  "A Storm of Swords",
                  "Of the five contenders for power, one is dead, another in disfavor, and still the wars rage as violently as ever, as alliances are made and broken. Joffrey, of House Lannister, sits on the Iron Throne, the uneasy ruler of the land of the Seven Kingdoms. His most bitter rival, Lord Stannis, stands defeated and disgraced, the victim of the jealous sorceress who holds him in her evil thrall. But young Robb, of House Stark, still rules the North from the fortress of Riverrun. Robb plots against his despised Lannister enemies, even as they hold his sister hostage at King’s Landing, the seat of the Iron Throne. Meanwhile, making her way across a blood-drenched continent is the exiled queen, Daenerys, mistress of the only three dragons still left in the world. But as opposing forces maneuver for the final titanic showdown, an army of barbaric wildlings arrives from the outermost line of civilization. In their vanguard is a horde of mythical Others--a supernatural army of the living dead whose animated corpses are unstoppable. As the future of the land hangs in the balance, no one will rest until the Seven Kingdoms have exploded in a veritable storm of swords.",
                  24.99,
                  0,
                  "storage/images/books/a-storm-of-swords.jpeg"
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
            [2, 6],
            [4, 6],
            [5, 2],
            [6, 4],
            [7, 3],
            [8, 5],
            [9, 3],
            [10, 3]
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
            [4, 6],
            [5, 1],
            [5, 2],
            [6, 3],
            [7, 1],
            [7, 5],
            [8, 5],
            [9, 1],
            [9, 2],
            [10, 1],
            [10, 2]
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