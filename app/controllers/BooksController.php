<?php

namespace app\controllers;

use app\models\BooksModel;
use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;
use libs\File;

class BooksController
{
    protected $booksModel;

    public function __construct()
    {
        $this->booksModel = new BooksModel();
    }

    public function index()
    {
        $books = $this->booksModel->getAllBooks();

        return View::render([
            'data' => $books
        ]);
    }

    public function show($id)
    {
        $book = $this->booksModel->getBookById($id);

        if (count($book) === 0)
        {
            return View::render([
                'text' => "Book with id $id not found."
            ], 404);
        }

        return View::render([
            'data' => $book
        ]);
    }

    public function store()
    {
        $user = Auth::check();

        if ('admin' !== $user['role'])
        {
            return View::render([
                'text' => "Route permission denied."
            ], 403);
        }

        $dbPrefix = $this->booksModel->getDbPrefix();

        $validationErrors = Validator::validate([
            'title' => "required|unique:{$dbPrefix}books:title",
            'description' => "required|minLength:20",
            'price' => "required|numeric",
            'discount' => "required|numeric|min:0",
            'author' => "integer|min:1|exists:{$dbPrefix}authors:id",
            'genre' => "integer|min:1|exists:{$dbPrefix}genres:id"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $title = Input::get('title');
        $description = Input::get('description');
        $price = Input::get('price');
        $discount = Input::get('discount');
        $author = Input::get('author');
        $genre = Input::get('genre');

        $this->booksModel->addBook($title, $description, $price, $discount, $author, $genre);

        return View::render([
            'text' => "Book $title was successfully added."
        ]);
    }

    public function storeImage($id)
    {
        $book = $this->booksModel->getBookById($id);

        if (count($book) === 0)
        {
            return View::render([
                'text' => "Book with id $id not found."
            ], 404);
        }
        
        $image = File::get('image');

        if (!$image->isExistsInInput())
        {
            return View::render([
                'text' => 'File input required.'
            ], 422);
        }

        if (!$image->isImage())
        {
            return View::render([
                'text' => 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.'
            ], 422);
        }

        $this->booksModel->addImage($book[0], $image);

        return View::render([
            'text' => "Image for book '{$book[0]['title']}' was successfully added."
        ]);
    }

    public function update($id)
    {
        var_dump("update $id");
    }

    public function delete($id)
    {
        var_dump("delete $id");
    }
}

/*
    1) storeImage добавить auth и admin проверки
    2) book title - alphanumeric валидацию добавить
    3) в миграции проставить not null на поля таблиц books, users, genres, authors
*/