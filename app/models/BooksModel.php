<?php

namespace app\models;

use libs\File;

class BooksModel extends Model
{
    public function getAllBooks($author = null, $genre = null, $title = null)
    {
        $dbPrefix = self::$dbPrefix;

        $executeParams = [];
        $sqlQuery = "
            SELECT
                {$dbPrefix}books.id,
                {$dbPrefix}books.title,
                {$dbPrefix}books.description,
                {$dbPrefix}books.image_url,
                {$dbPrefix}books.price,
                {$dbPrefix}books.discount,
                GROUP_CONCAT(DISTINCT {$dbPrefix}authors.name) AS authors,
                GROUP_CONCAT(DISTINCT {$dbPrefix}genres.name) AS genres
            FROM {$dbPrefix}books
            LEFT JOIN {$dbPrefix}book_author 
                ON {$dbPrefix}books.id = {$dbPrefix}book_author.book_id
            LEFT JOIN {$dbPrefix}authors
                ON {$dbPrefix}authors.id = {$dbPrefix}book_author.author_id
            LEFT JOIN {$dbPrefix}book_genre 
                ON {$dbPrefix}books.id = {$dbPrefix}book_genre.book_id
            LEFT JOIN {$dbPrefix}genres
                ON {$dbPrefix}genres.id = {$dbPrefix}book_genre.genre_id
            WHERE 1=1
        ";

        if ($author && strlen($author) > 0)
        {
            $sqlQuery .= " AND {$dbPrefix}authors.id = ?";
            $executeParams[] = $author;
        }

        if ($genre && strlen($genre) > 0)
        {
            $sqlQuery .= " AND {$dbPrefix}genres.id = ?";
            $executeParams[] = $genre;
        }

        if ($title && strlen($title) > 0)
        {
            $sqlQuery .= " AND {$dbPrefix}books.title LIKE ?";
            $executeParams[] = "%$title%";
        }

        $sqlQuery .= " GROUP BY {$dbPrefix}books.id";

        $books = self::$builder->raw($sqlQuery, $executeParams);
        $books = $books->fetchAll(\PDO::FETCH_ASSOC);
        $books = array_map(function($book) {
            $book['authors'] = explode(',', $book['authors']);
            $book['genres'] = explode(',', $book['genres']);

            return $book;
        }, $books);

        return $books;
    }

    public function getBookById($id)
    {
        $dbPrefix = self::$dbPrefix;

        $executeParams = [];
        $sqlQuery = "
            SELECT
                {$dbPrefix}books.id,
                {$dbPrefix}books.title,
                {$dbPrefix}books.description,
                {$dbPrefix}books.image_url,
                {$dbPrefix}books.price,
                {$dbPrefix}books.discount,
                GROUP_CONCAT(DISTINCT {$dbPrefix}authors.name) AS authors,
                GROUP_CONCAT(DISTINCT {$dbPrefix}genres.name) AS genres
            FROM {$dbPrefix}books
            LEFT JOIN {$dbPrefix}book_author 
                ON {$dbPrefix}books.id = {$dbPrefix}book_author.book_id
            LEFT JOIN {$dbPrefix}authors
                ON {$dbPrefix}authors.id = {$dbPrefix}book_author.author_id
            LEFT JOIN {$dbPrefix}book_genre 
                ON {$dbPrefix}books.id = {$dbPrefix}book_genre.book_id
            LEFT JOIN {$dbPrefix}genres
                ON {$dbPrefix}genres.id = {$dbPrefix}book_genre.genre_id
            WHERE {$dbPrefix}books.id = ?
            GROUP BY {$dbPrefix}books.id
        ";
        
        $executeParams[] = $id;

        $books = self::$builder->raw($sqlQuery, $executeParams);
        $books = $books->fetchAll(\PDO::FETCH_ASSOC);
        $books = array_map(function($book) {
            $book['authors'] = explode(',', $book['authors']);
            $book['genres'] = explode(',', $book['genres']);

            return $book;
        }, $books);

        return $books;
    }

    public function addBook($title, $description, $price, $discount)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}books")
                    ->fields(['title', 'description', 'price', 'discount'])
                    ->values([$title, $description, $price, $discount])
                    ->insert()
                    ->run();
    }

    public function addAuthors($bookId, $author)
    {
        $dbPrefix = self::$dbPrefix;

        $this->deleteAuthors($bookId);

        if (is_array($author))
        {
            $valuesArray = [];

            foreach($author as $row)
            {
                $valuesArray[] = [+$bookId, +$row];
            }

            self::$builder->table("{$dbPrefix}book_author")
                ->fields(['book_id', 'author_id'])
                ->values(...$valuesArray)
                ->insert()
                ->run();
        }
        else
        {
            self::$builder->table("{$dbPrefix}book_author")
                ->fields(['book_id', 'author_id'])
                ->values([+$bookId, +$author])
                ->insert()
                ->run();
        }
    }

    public function addGenres($bookId, $genre)
    {
        $dbPrefix = self::$dbPrefix;

        $this->deleteGenres($bookId);

        if (is_array($genre))
        {
            $valuesArray = [];

            foreach($genre as $row)
            {
                $valuesArray[] = [+$bookId, +$row];
            }

            self::$builder->table("{$dbPrefix}book_genre")
                ->fields(['book_id', 'genre_id'])
                ->values(...$valuesArray)
                ->insert()
                ->run();
        }
        else
        {
            self::$builder->table("{$dbPrefix}book_genre")
                ->fields(['book_id', 'genre_id'])
                ->values([+$bookId, +$genre])
                ->insert()
                ->run();
        }
    }

    public function addImage($book, File $image)
    {
        $dbPrefix = self::$dbPrefix;
        $uploadedFileName = $image->move(STORAGE_PATH . '/images/books', $book['title']);
        $storageFilePath = "storage/images/books/$uploadedFileName";

        self::$builder->table("{$dbPrefix}books")
            ->fields(['image_url'])
            ->values([$storageFilePath])
            ->where(['id', '=', $book['id']])
            ->update()
            ->run();
    }

    public function updateBook($id, $title, $description, $price, $discount)
    {
        $dbPrefix = self::$dbPrefix;

        self::$builder->table("{$dbPrefix}books")
            ->fields(['title', 'description', 'price', 'discount'])
            ->values([$title, $description, $price, $discount])
            ->where(['id', '=', $id])
            ->update()
            ->run();
    }

    public function deleteAuthors($id)
    {
        $dbPrefix = self::$dbPrefix;
        
        self::$builder->table("{$dbPrefix}book_author")
            ->where(['book_id', '=', $id])
            ->delete()
            ->run();
    }

    public function deleteGenres($id)
    {
        $dbPrefix = self::$dbPrefix;
        
        self::$builder->table("{$dbPrefix}book_genre")
            ->where(['book_id', '=', $id])
            ->delete()
            ->run();
    }
}