<?php

namespace app\controllers;

use libs\View;
use libs\Validator;
use libs\Input;
use libs\File;

use app\models\BooksModel;

class BooksController
{
    protected $booksModel;

    public function __construct()
    {
        $this->booksModel = new BooksModel();
    }

    public function index()
    {
        $authorId = Input::get('author_id');
        $genreId = Input::get('genre_id');
        $title = Input::get('title');

        $books = $this->booksModel->getAllBooks($authorId, $genreId, $title);

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
        $maxDiscount = MAX_DISCOUNT;

        $validator = Validator::make([
            'title' => "required|unique:books:title|alpha_dash",
            'description' => "required|minLength:20",
            'price' => "required|numeric",
            'discount' => "required|numeric|min:0|max:$maxDiscount"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $title = Input::get('title');
        $description = Input::get('description');
        $price = Input::get('price');
        $discount = Input::get('discount');

        $id = $this->booksModel->addBook($title, $description, $price, $discount);

        return View::render([
            'text' => "Book '$title' was successfully added.",
            'data' => ['id' => $id]
        ]);
    }

    public function storeAuthors($id)
    {
        $validator = Validator::make([
            'author' => "required|integer|min:1|exists:authors:id"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
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
        $validator = Validator::make([
            'genre' => "required|integer|min:1|exists:genres:id"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
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
        $maxDiscount = MAX_DISCOUNT;

        $validator = Validator::make([
            'title' => "required|unique:books:title:$id|alpha_dash",
            'description' => "required|minLength:20",
            'price' => "required|numeric",
            'discount' => "required|numeric|min:0|max:$maxDiscount"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $title = Input::get('title');
        $description = Input::get('description');
        $price = Input::get('price');
        $discount = Input::get('discount');

        $this->booksModel->updateBook($id, $title, $description, $price, $discount);

        return View::render([
            'text' => "Book '$title' was successfully updated."
        ]);
    }

    public function delete($id)
    {
        $this->booksModel->deleteBook($id);

        return View::render([
            'text' => "Book with id '$id' was successfully deleted."
        ]);
    }
}