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

        if (count($user) === 0)
        {
            return View::render([
                'text' => "User with id '$id' not found."
            ], 404);
        }

        return View::render([
            'data' => $user
        ]);
    }

    public function store()
    {
        $dbPrefix = $this->usersModel->getDbPrefix();
        
        $validationErrors = Validator::validate([
            'name' => "required|minLength:6",
            'email' => "required|email|unique:{$dbPrefix}users:email",
            'password' => "required|minLength:6"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
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
        $user = $this->usersModel->getUserById($id);

        if (count($user) === 0)
        {
            return View::render([
                'text' => "User with id '$id' not found."
            ], 404);
        }

        $dbPrefix = $this->usersModel->getDbPrefix();
        
        $validationErrors = Validator::validate([
            'name' => "required|minLength:6",
            'email' => "required|email|unique:{$dbPrefix}users:email:{$id}",
            'password' => "required|minLength:6",
            'discount' => "numeric"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
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

    public function updateCurrentAuth()
    {
        $user = Auth::user();

        $dbPrefix = $this->usersModel->getDbPrefix();
        
        $validationErrors = Validator::validate([
            'name' => "required|minLength:6",
            'email' => "required|email|unique:{$dbPrefix}users:email:{$user['id']}",
            'password' => "required|minLength:6"
        ]);

        if (count($validationErrors) > 0)
        {
            return View::render([
                'text' => 'The credentials you supplied were not correct.',
                'data' => $validationErrors
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