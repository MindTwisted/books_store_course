<?php

namespace app\controllers;

use app\models\AuthorsModel;
use app\models\BooksModel;
use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

class AuthorsController
{
    protected $authorsModel;

    public function __construct()
    {
        $this->authorsModel = new AuthorsModel();
        $this->booksModel = new BooksModel();
    }

    public function index()
    {
        $authors = $this->authorsModel->getAllAuthors();

        return View::render([
            'data' => $authors
        ]);
    }

    public function show($id)
    {
        $author = $this->authorsModel->getAuthorById($id);

        return View::render([
            'data' => $author
        ]);
    }

    public function showBooks($id)
    {
        $books = $this->booksModel->getAllBooks($id);

        return View::render([
            'data' => $books
        ]);
    }

    public function store()
    {   
        $validator = Validator::make([
            'name' => "required|unique:authors:name"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');

        $id = $this->authorsModel->addAuthor($name);

        return View::render([
            'text' => "Author '$name' was successfully added.",
            'data' => ['id' => $id]
        ]);
    }

    public function update($id)
    {
        $validator = Validator::make([
            'name' => "required|unique:authors:name:$id"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');

        $this->authorsModel->updateAuthor($id, $name);

        return View::render([
            'text' => "Author '$name' was successfully updated."
        ]);
    }

    public function delete($id)
    {
        $this->authorsModel->deleteAuthor($id);

        return View::render([
            'text' => "Author with id '$id' was successfully deleted."
        ]);
    }
}