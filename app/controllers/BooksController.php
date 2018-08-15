<?php

namespace app\controllers;

class BooksController
{
    public function index()
    {
        echo 'books index';
    }

    public function show($id)
    {
        echo "books show $id";
    }
}