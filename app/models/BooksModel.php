<?php

namespace app\models;

class BooksModel extends Model
{
    public function getAllBooks()
    {
        $executeParams = [];
        $sqlQuery = "
            SELECT
                {$this->dbPrefix}books.id,
                {$this->dbPrefix}books.title,
                {$this->dbPrefix}books.description,
                {$this->dbPrefix}books.image_url,
                {$this->dbPrefix}books.price,
                {$this->dbPrefix}books.discount,
                GROUP_CONCAT(DISTINCT {$this->dbPrefix}authors.name) AS authors,
                GROUP_CONCAT(DISTINCT {$this->dbPrefix}genres.name) AS genres
            FROM {$this->dbPrefix}books
            INNER JOIN {$this->dbPrefix}book_author 
                ON {$this->dbPrefix}books.id = {$this->dbPrefix}book_author.book_id
            INNER JOIN {$this->dbPrefix}authors
                ON {$this->dbPrefix}authors.id = {$this->dbPrefix}book_author.author_id
            INNER JOIN {$this->dbPrefix}book_genre 
                ON {$this->dbPrefix}books.id = {$this->dbPrefix}book_genre.book_id
            INNER JOIN {$this->dbPrefix}genres
                ON {$this->dbPrefix}genres.id = {$this->dbPrefix}book_genre.genre_id
            WHERE 1=1
        ";

        if (isset($_GET['author']) 
            && strlen($_GET['author']) > 0)
        {
            $sqlQuery .= " AND {$this->dbPrefix}authors.name = ?";
            $executeParams[] = $_GET['author'];
        }

        if (isset($_GET['genre']) 
            && strlen($_GET['genre']) > 0)
        {
            $sqlQuery .= " AND {$this->dbPrefix}genres.name = ?";
            $executeParams[] = $_GET['genre'];
        }

        if (isset($_GET['title']) 
            && strlen($_GET['title']) > 0)
        {
            $sqlQuery .= " AND {$this->dbPrefix}books.title LIKE ?";
            $executeParams[] = "%{$_GET['title']}%";
        }

        $sqlQuery .= " GROUP BY {$this->dbPrefix}books.id";

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
        $executeParams = [];
        $sqlQuery = "
            SELECT
                {$this->dbPrefix}books.id,
                {$this->dbPrefix}books.title,
                {$this->dbPrefix}books.description,
                {$this->dbPrefix}books.image_url,
                {$this->dbPrefix}books.price,
                {$this->dbPrefix}books.discount,
                GROUP_CONCAT(DISTINCT {$this->dbPrefix}authors.name) AS authors,
                GROUP_CONCAT(DISTINCT {$this->dbPrefix}genres.name) AS genres
            FROM {$this->dbPrefix}books
            INNER JOIN {$this->dbPrefix}book_author 
                ON {$this->dbPrefix}books.id = {$this->dbPrefix}book_author.book_id
            INNER JOIN {$this->dbPrefix}authors
                ON {$this->dbPrefix}authors.id = {$this->dbPrefix}book_author.author_id
            INNER JOIN {$this->dbPrefix}book_genre 
                ON {$this->dbPrefix}books.id = {$this->dbPrefix}book_genre.book_id
            INNER JOIN {$this->dbPrefix}genres
                ON {$this->dbPrefix}genres.id = {$this->dbPrefix}book_genre.genre_id
            WHERE {$this->dbPrefix}books.id = ?
            GROUP BY {$this->dbPrefix}books.id
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
}