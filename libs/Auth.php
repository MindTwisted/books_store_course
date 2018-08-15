<?php

namespace libs;

use libs\QueryBuilder\src\QueryBuilder;
use libs\View;

class Auth
{
    private static $builder = null;

    private static function setBuilder()
    {
        if (null === self::$builder)
        {
            self::$builder = new QueryBuilder(
                'mysql',
                MYSQL_SETTINGS['host'],
                MYSQL_SETTINGS['port'],
                MYSQL_SETTINGS['database'],
                MYSQL_SETTINGS['user'],
                MYSQL_SETTINGS['password']
            );
        }
    }

    public static function login($user)
    {
        self::setBuilder();

        $token = bin2hex(openssl_random_pseudo_bytes(50));
        $expiresAt = date ("Y-m-d H:i:s", time() + AUTH_TOKEN_EXPIRES);

        self::$builder->table(TABLE_PREFIX . 'auth_tokens')
            ->fields(['user_id', 'token', 'expires_at'])
            ->values([$user['id'], $token, $expiresAt])
            ->insert()
            ->run();

        return $token;
    }

    public static function logout($user)
    {
        self::setBuilder();

        self::$builder->table(TABLE_PREFIX . 'auth_tokens')
            ->where(['user_id', '=', $user['id']])
            ->delete()
            ->run();
    }

    public static function check()
    {
        self::setBuilder();

        $headers = getallheaders();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if ($authHeader === null)
        {
            return View::render([
                'text' => "You must be a authenticated user to process this request."
            ], 401);
        }

        $token = explode(' ', $authHeader);
        $token = isset($token[1]) ? $token[1] : '';
        $datetimeNow = date ("Y-m-d H:i:s", time());

        $authToken = self::$builder->table(TABLE_PREFIX . 'auth_tokens')
            ->fields(['user_id', 'token'])
            ->where(['token', '=', $token])
            ->andWhere(['expires_at', '>', $datetimeNow])
            ->select()
            ->run();

        if (count($authToken) === 0)
        {
            return View::render([
                'text' => "You must be a authenticated user to process this request."
            ], 401);
        }
        
        $user = self::$builder->table(TABLE_PREFIX . 'users')
            ->fields(['id', 'name', 'email', 'role', 'discount'])
            ->where(['id', '=', $authToken[0]['user_id']])
            ->select()
            ->run();

        if (count($user) === 0)
        {
            return View::render([
                'text' => "You must be a authenticated user to process this request."
            ], 401);
        }

        return $user[0];
    }
}