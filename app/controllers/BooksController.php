<?php

namespace app\controllers;

use app\models\BooksModel;
use libs\View;

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
        var_dump('store book');
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