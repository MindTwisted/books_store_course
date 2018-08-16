<?php

namespace app\controllers;

use app\models\AuthorsModel;
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

        if (count($author) === 0)
        {
            return View::render([
                'text' => "Author with id $id not found."
            ], 404);
        }

        return View::render([
            'data' => $author
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

        $dbPrefix = $this->authorsModel->getDbPrefix();
        
        $validationErrors = Validator::validate([
            'name' => "required|unique:{$dbPrefix}authors:name"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $name = Input::get('name');

        $this->authorsModel->addAuthor($name);

        return View::render([
            'text' => "Author $name was successfully added."
        ]);
    }

    public function update($id)
    {
        $user = Auth::check();

        if ('admin' !== $user['role'])
        {
            return View::render([
                'text' => "Route permission denied."
            ], 403);
        }

        $dbPrefix = $this->authorsModel->getDbPrefix();

        $validationErrors = Validator::validate([
            'name' => "required|unique:{$dbPrefix}authors:name:{$id}"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $author = $this->authorsModel->getAuthorById($id);

        if (count($author) === 0)
        {
            return View::render([
                'text' => "Author with id $id not found."
            ], 404);
        }

        $name = Input::get('name');

        $this->authorsModel->updateAuthor($id, $name);

        return View::render([
            'text' => "Author $name was successfully updated."
        ]);
    }

    public function delete($id)
    {
        $user = Auth::check();

        if ('admin' !== $user['role'])
        {
            return View::render([
                'text' => "Route permission denied."
            ], 403);
        }

        $author = $this->authorsModel->getAuthorById($id);

        if (count($author) === 0)
        {
            return View::render([
                'text' => "Author with id $id not found."
            ], 404);
        }

        $this->authorsModel->deleteAuthor($id);

        return View::render([
            'text' => "Author {$author[0]['name']} was successfully deleted."
        ]);
    }
}