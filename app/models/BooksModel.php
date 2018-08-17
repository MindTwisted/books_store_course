<?php

namespace app\models;

use libs\Input;

class BooksModel extends Model
{
    public function getAllBooks()
    {
        $author = Input::get('author');
        $genre = Input::get('genre');
        $title = Input::get('title');

        $dbPrefix = $this->getDbPrefix();

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
            $sqlQuery .= " AND {$dbPrefix}authors.name = ?";
            $executeParams[] = $author;
        }

        if ($genre && strlen($genre) > 0)
        {
            $sqlQuery .= " AND {$dbPrefix}genres.name = ?";
            $executeParams[] = $genre;
        }

        if ($title && strlen($title) > 0)
        {
            $sqlQuery .= " AND {$dbPrefix}books.title LIKE ?";
            $executeParams[] = "%$title%";
        }

        $sqlQuery .= " GROUP BY {$dbPrefix}books.id";

        $books = $this->queryBuilder->raw($sqlQuery, $executeParams);

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
        $dbPrefix = $this->getDbPrefix();

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

        $books = $this->queryBuilder->raw($sqlQuery, $executeParams);

        $books = $books->fetchAll(\PDO::FETCH_ASSOC);

        $books = array_map(function($book) {
            $book['authors'] = explode(',', $book['authors']);
            $book['genres'] = explode(',', $book['genres']);

            return $book;
        }, $books);

        return $books;
    }

    public function addBook($title, $description, $price, $discount, $author, $genre)
    {
        $dbPrefix = $this->getDbPrefix();

        $this->queryBuilder->table("{$dbPrefix}books")
            ->fields(['title', 'description', 'price', 'discount'])
            ->values([$title, $description, $price, $discount])
            ->insert()
            ->run();

        $bookId = $this->queryBuilder->table("{$dbPrefix}books")
            ->fields(['id'])
            ->where(['title', '=', $title])
            ->limit(1)
            ->select()
            ->run()[0]['id'];

        if (null !== $author)
        {
            if (is_array($author))
            {
                $valuesArray = [];

                foreach($author as $row)
                {
                    $valuesArray[] = [+$bookId, +$row];
                }

                $this->queryBuilder->table("{$dbPrefix}book_author")
                    ->fields(['book_id', 'author_id'])
                    ->values(...$valuesArray)
                    ->insert()
                    ->run();
            }
            else
            {
                $this->queryBuilder->table("{$dbPrefix}book_author")
                    ->fields(['book_id', 'author_id'])
                    ->values([+$bookId, +$author])
                    ->insert()
                    ->run();
            }
        }

        if (null !== $genre)
        {
            if (is_array($genre))
            {
                $valuesArray = [];

                foreach($genre as $row)
                {
                    $valuesArray[] = [+$bookId, +$row];
                }

                $this->queryBuilder->table("{$dbPrefix}book_genre")
                    ->fields(['book_id', 'genre_id'])
                    ->values(...$valuesArray)
                    ->insert()
                    ->run();
            }
            else
            {
                $this->queryBuilder->table("{$dbPrefix}book_genre")
                    ->fields(['book_id', 'genre_id'])
                    ->values([+$bookId, +$genre])
                    ->insert()
                    ->run();
            }
        }
        
    }
}