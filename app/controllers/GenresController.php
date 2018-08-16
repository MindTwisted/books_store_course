<?php

namespace app\controllers;

use app\models\GenresModel;
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
        $genres = $this->genresModel->getGenreById($id);

        if (count($genres) === 0)
        {
            return View::render([
                'text' => "Genre with id $id not found."
            ], 404);
        }

        return View::render([
            'data' => $genres
        ]);
    }

    public function store()
    {
        $user = Auth::check();
        $dbPrefix = $this->genresModel->getDbPrefix();
        
        $validationErrors = Validator::validate([
            'name' => "required|unique:{$dbPrefix}genres:name"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $name = Input::get('name');

        $this->genresModel->addGenre($name);

        return View::render([
            'text' => "Genre $name was successfully added."
        ]);
    }

    public function update($id)
    {
        $user = Auth::check();
        $dbPrefix = $this->genresModel->getDbPrefix();

        $validationErrors = Validator::validate([
            'name' => "required|unique:{$dbPrefix}genres:name:{$id}"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $genre = $this->genresModel->getGenreById($id);

        if (count($genre) === 0)
        {
            return View::render([
                'text' => "Genre with id $id not found."
            ], 404);
        }

        $name = Input::get('name');

        $this->genresModel->updateGenre($id, $name);

        return View::render([
            'text' => "Genre $name was successfully updated."
        ]);
    }

    public function delete($id)
    {
        $user = Auth::check();

        $genre = $this->genresModel->getGenreById($id);

        if (count($genre) === 0)
        {
            return View::render([
                'text' => "Genre with id $id not found."
            ], 404);
        }

        $this->genresModel->deleteGenre($id);

        return View::render([
            'text' => "Genre {$genre[0]['name']} was successfully deleted."
        ]);
    }
}