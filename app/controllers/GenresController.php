<?php

namespace app\controllers;

use app\models\GenresModel;
use app\models\BooksModel;
use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

class GenresController
{
    protected $genresModel;

    public function __construct()
    {
        $this->genresModel = new GenresModel();
        $this->booksModel = new BooksModel();
    }

    public function index()
    {
        $genres = $this->genresModel->getAllGenres();

        return View::render([
            'data' => $genres
        ]);
    }

    public function show($id)
    {
        $genre = $this->genresModel->getGenreById($id);

        return View::render([
            'data' => $genre
        ]);
    }

    public function showBooks($id)
    {
        $books = $this->booksModel->getAllBooks(null, $id);

        return View::render([
            'data' => $books
        ]);
    }

    public function store()
    {
        $validator = Validator::make([
            'name' => "required|unique:genres:name"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');

        $this->genresModel->addGenre($name);

        return View::render([
            'text' => "Genre '$name' was successfully added."
        ]);
    }

    public function update($id)
    {
        $validator = Validator::make([
            'name' => "required|unique:genres:name:$id"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');

        $this->genresModel->updateGenre($id, $name);

        return View::render([
            'text' => "Genre '$name' was successfully updated."
        ]);
    }

    public function delete($id)
    {
        $this->genresModel->deleteGenre($id);

        return View::render([
            'text' => "Genre with id '$id' was successfully deleted."
        ]);
    }
}