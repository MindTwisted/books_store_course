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
                    'text' => "User '{$user['name']}' was successfully logged in.",
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

    public function delete()
    {
        $user = Auth::user();

        Auth::logout($user);
        
        return View::render([
            'text' => "User '{$user['name']}' was successfully logged out."
        ]);  
    }
}