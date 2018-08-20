<?php

namespace app\controllers;

use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

use app\models\UsersModel;

class AuthController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        $user = Auth::user();
        
        return View::render([
            'data' => $user
        ]);
    }

    public function store()
    {
        $validator = Validator::make([
            'email' => "required|email",
            'password' => "required"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $email = Input::get('email');
        $password = Input::get('password');

        $login = Auth::login($email, $password);

        return View::render([
            'text' => "User '{$login['name']}' was successfully logged in.",
            'data' => [
                'token' => $login['token'],
                'role' => $login['role']
            ]
        ]);        
    }

    public function delete()
    {
        $user = Auth::user();

        Auth::logout();
        
        return View::render([
            'text' => "User '{$user['name']}' was successfully logged out."
        ]);  
    }
}