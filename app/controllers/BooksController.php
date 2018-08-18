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

        return View::render([
            'data' => $book
        ]);
    }

    public function store()
    {
        $validationErrors = Validator::validate([
            'title' => "required|unique:books:title|alpha_dash",
            'description' => "required|minLength:20",
            'price' => "required|numeric",
            'discount' => "required|numeric|min:0"
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

        $this->booksModel->addBook($title, $description, $price, $discount);

        return View::render([
            'text' => "Book '$title' was successfully added."
        ]);
    }

    public function storeAuthors($id)
    {
        $validationErrors = Validator::validate([
            'author' => "required|integer|min:1|exists:authors:id"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $author = Input::get('author');

        $this->booksModel->addAuthors($id, $author);

        return View::render([
            'text' => "Authors for book id '$id' was successfully added."
        ]);
    }

    public function storeGenres($id)
    {
        $validationErrors = Validator::validate([
            'genre' => "required|integer|min:1|exists:genres:id"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $genre = Input::get('genre');

        $this->booksModel->addGenres($id, $genre);

        return View::render([
            'text' => "Genres for book id '$id' was successfully added."
        ]);
    }

    public function storeImage($id)
    {
        $book = $this->booksModel->getBookById($id);
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
        $validationErrors = Validator::validate([
            'title' => "required|unique:books:title:{$id}|alpha_dash",
            'description' => "required|minLength:20",
            'price' => "required|numeric",
            'discount' => "required|numeric|min:0",
            'author' => "integer|min:1|exists:authors:id",
            'genre' => "integer|min:1|exists:genres:id"
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

        $this->booksModel->updateBook($id, $title, $description, $price, $discount, $author, $genre);

        return View::render([
            'text' => "Book '$title' was successfully updated."
        ]);
    }

    public function delete($id)
    {
        var_dump("delete $id");
    }
}