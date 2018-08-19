<?php

namespace app\models;

class UsersModel extends Model
{
    public function getUserByEmail($email)
    {
        $dbPrefix = self::$dbPrefix;

        $user = self::$builder->table("{$dbPrefix}users")
            ->fields(['*'])
            ->where(['email', '=', $email])
            ->limit(1)
            ->select()
            ->run();

        return $user;
    }

    public function getAllUsers()
    {
        $dbPrefix = self::$dbPrefix;

        $users = self::$builder->table("{$dbPrefix}users")
            ->fields(['id', 'name', 'email', 'role', 'discount'])
            ->select()
            ->run();

        return $users;
    }

    public function getUserById($id)
    {
        $dbPrefix = self::$dbPrefix;

        $user = self::$builder->table("{$dbPrefix}users")
            ->fields(['id', 'name', 'email', 'role', 'discount'])
            ->where(['id', '=', $id])
            ->limit(1)
            ->select()
            ->run();

        return $user;
    }

    public function addUser($name, $email, $password)
    {
        $dbPrefix = self::$dbPrefix;

        return self::$builder->table("{$dbPrefix}users")
            ->fields(['name', 'email', 'password'])
            ->values([$name, $email, password_hash($password, PASSWORD_BCRYPT)])
            ->insert()
            ->run();
    }

    public function updateUser($id, $name, $email, $password, $discount = null)
    {
        $dbPrefix = self::$dbPrefix;

        $fields = null === $discount ?
            ['name', 'email', 'password'] :
            ['name', 'email', 'password', 'discount'];

        $values = null === $discount ?
            [$name, $email, password_hash($password, PASSWORD_BCRYPT)] :
            [$name, $email, password_hash($password, PASSWORD_BCRYPT), $discount];

        self::$builder->table("{$dbPrefix}users")
            ->fields($fields)
            ->values($values)
            ->where(['id', '=', $id])
            ->limit(1)
            ->update()
            ->run();
    }
}