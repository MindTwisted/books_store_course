<?php

namespace app\controllers;

use app\models\BooksModel;
use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

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

    public function update($id)
    {
        var_dump("update $id");
    }

    public function delete($id)
    {
        var_dump("delete $id");
    }
}