<?php

namespace app\controllers;

use app\models\UsersModel;
use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

class AuthController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        $user = Auth::check();
        
        return View::render([
            'data' => $user
        ]);
    }

    public function store()
    {
        $validationErrors = Validator::validate([
            'email' => "required|email",
            'password' => "required"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
            ], 422);
        }

        $email = Input::get('email');
        $password = Input::get('password');

        $user = $this->usersModel->getUserByEmail($email);

        if (count($user) > 0)
        {
            $user = $user[0];
            
            if (password_verify(
                $password,
                $user['password'])
            )
            {
                $token = Auth::login($user);

                return View::render([
                    'text' => "User {$user['name']} was successfully logged in.",
                    'data' => [
                        'token' => $token,
                        'role' => $user['role']
                    ]
                ]);
            }     
        }  
        return View::render([
            'text' => "The credentials you supplied were not correct."
        ], 401);  
    }
}