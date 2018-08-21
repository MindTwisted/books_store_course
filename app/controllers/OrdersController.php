<?php

namespace app\controllers;

use libs\Auth;
use libs\View;
use libs\Validator;
use libs\Input;

use app\models\OrdersModel;
use app\models\CartModel;

class OrdersController
{
    protected $ordersModel;
    protected $cartModel;

    public function __construct()
    {
        $this->ordersModel = new OrdersModel();
        $this->cartModel = new CartModel();
    }

    public function store()
    {
        $user = Auth::user();

        $validator = Validator::make([
            'payment_type' => "required|exists:payment_types:id"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $cart = $this->cartModel->getUsersCart($user['id']);

        if (count($cart) === 0)
        {
            View::render([
                'text' => "There are no books in cart."
            ], 409);
        }

        $paymentType = Input::get('payment_type');

        $orderId = $this->ordersModel->addOrder($user, $cart, $paymentType);

        return View::render([
            'text' => "Order with id '$orderId' was successfully added."
        ]);
    }
}