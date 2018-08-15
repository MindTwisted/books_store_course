<?php

namespace app\models;

class UsersModel extends Model
{
    public function getUserByEmail($email)
    {
        $user = $this->queryBuilder->table("{$this->dbPrefix}users")
            ->fields(['*'])
            ->where(['email', '=', $email])
            ->select()
            ->run();

        return $user;
    }
}