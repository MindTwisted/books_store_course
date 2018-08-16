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

        return View::render([
            'data' => $author
        ]);
    }

    public function store()
    {
        $user = Auth::check();
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

        var_dump("authors update $id");
    }

    public function delete($id)
    {
        var_dump("authors delete $id");
    }
}