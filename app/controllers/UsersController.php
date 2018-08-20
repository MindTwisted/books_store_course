<?php

namespace app\controllers;

use app\models\UsersModel;
use libs\View;
use libs\Auth;
use libs\Validator;
use libs\Input;

class UsersController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        $users = $this->usersModel->getAllUsers();

        return View::render([
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = $this->usersModel->getUserById($id);

        return View::render([
            'data' => $user
        ]);
    }

    public function store()
    {    
        $validator = Validator::make([
            'name' => "required|minLength:6",
            'email' => "required|email|unique:users:email",
            'password' => "required|minLength:6"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');
        $email = Input::get('email');
        $password = Input::get('password');

        $this->usersModel->addUser($name, $email, $password);

        return View::render([
            'text' => "User '$name' was successfully registered."
        ]);
    }

    public function update($id)
    {
        $validator = Validator::make([
            'name' => "required|minLength:6",
            'email' => "required|email|unique:users:email:$id",
            'password' => "required|minLength:6",
            'discount' => "numeric"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');
        $email = Input::get('email');
        $password = Input::get('password');
        $discount = Input::get('discount');

        $this->usersModel->updateUser($id, $name, $email, $password, $discount);

        return View::render([
            'text' => "User '$name' was successfully updated."
        ]);
    }

    public function updateCurrent()
    {
        $user = Auth::user();
        
        $validator = Validator::make([
            'name' => "required|minLength:6",
            'email' => "required|email|unique:users:email:{$user['id']}",
            'password' => "required|minLength:6"
        ]);

        if ($validator->fails())
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validator->errors()
            ], 422);
        }

        $name = Input::get('name');
        $email = Input::get('email');
        $password = Input::get('password');

        $this->usersModel->updateUser($user['id'], $name, $email, $password);

        return View::render([
            'text' => "User '$name' was successfully updated."
        ]);
    }
}