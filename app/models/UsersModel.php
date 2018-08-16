<?php

namespace app\models;

class UsersModel extends Model
{
    public function getUserByEmail($email)
    {
        $dbPrefix = $this->getDbPrefix();

        $user = $this->queryBuilder->table("{$dbPrefix}users")
            ->fields(['*'])
            ->where(['email', '=', $email])
            ->select()
            ->run();

        return $user;
    }
}