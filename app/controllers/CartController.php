<?php

namespace app\controllers;

use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

use app\models\CartModel;

class CartController
{
    protected $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    public function index()
    {
        $user = Auth::user();

        $cart = $this->cartModel->getUsersCart($user['id']);

        return View::render([
            'data' => $cart
        ]);
    }

    public function store()
    {
        $user = Auth::user();
        
        $validator = Validator::make([
            'book_id' => "required|exists:books:id",
            'count' => "required|integer|min:1"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $bookId = Input::get('book_id');
        $count = Input::get('count');

        $isAdded = $this->cartModel->addToCart($user['id'], $bookId, $count);

        if (!$isAdded)
        {
            View::render([
                'text' => "Book with id '$bookId' is already in cart."
            ], 409);
        }

        return View::render([
            'text' => "Book with id '$bookId' was successfully added to cart."
        ]);
    }

    public function update()
    {
        $user = Auth::user();
        
        $validator = Validator::make([
            'book_id' => "required|exists:books:id",
            'count' => "required|integer|min:1"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $bookId = Input::get('book_id');
        $count = Input::get('count');

        $isUpdated = $this->cartModel->updateInCart($user['id'], $bookId, $count);

        if (!$isUpdated)
        {
            View::render([
                'text' => "Book with id '$bookId' isn't in cart."
            ], 404);
        }

        return View::render([
            'text' => "Book with id '$bookId' was successfully updated in cart."
        ]);
    }

    public function delete()
    {
        $user = Auth::user();
        
        $validator = Validator::make([
            'book_id' => "required|exists:books:id"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $bookId = Input::get('book_id');

        $isDeleted = $this->cartModel->deleteFromCart($user['id'], $bookId);

        if (!$isDeleted)
        {
            View::render([
                'text' => "Book with id '$bookId' isn't in cart."
            ], 404);
        }

        return View::render([
            'text' => "Book with id '$bookId' was successfully deleted from cart."
        ]);
    }

}