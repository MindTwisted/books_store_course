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
        if (isset($_GET['author'])
            || isset($_GET['genre']))
        {
            $books = $this->booksModel->getFiltered($_GET);

            return View::render([
                'data' => $books
            ]);
        }

        $books = $this->booksModel->getAll();

        return View::render([
            'data' => $books
        ]);
    }

    public function show($id)
    {
        echo "books show $id";
    }
}