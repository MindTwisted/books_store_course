<?php

namespace libs;

use libs\QueryBuilder\src\QueryBuilder;
use libs\View;

class Auth
{
    private static $builder = null;
    private static $dbPrefix = '';
    private static $tokenExpiresTime = 3600;
    private static $user = null;

    private $instanceUser = null;

    private function isAdmin()
    {
        return 'admin' === $this->instanceUser['role'];
    }

    public function __construct($user)
    {
        $this->instanceUser = $user;
    }

    public static function setDbPrefix($prefix)
    {
        self::$dbPrefix = $prefix;
    }

    public static function setBuilder(QueryBuilder $builder)
    {
        self::$builder = $builder;
    }

    public static function setTokenExpiresTime($time)
    {
        self::$tokenExpiresTime = $time;
    }

    public function checkAdmin()
    {
        if (!$this->isAdmin())
        {
            return View::render([
                'text' => "Permission denied."
            ], 403);
        }

        return true;
    }

    public static function login($user)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(50));
        $expiresAt = date ("Y-m-d H:i:s", time() + self::$tokenExpiresTime);

        self::$builder->table(self::$dbPrefix . 'auth_tokens')
            ->fields(['user_id', 'token', 'expires_at'])
            ->values([$user['id'], $token, $expiresAt])
            ->insert()
            ->run();

        return $token;
    }

    public static function logout($user)
    {
        self::$builder->table(self::$dbPrefix . 'auth_tokens')
            ->where(['user_id', '=', $user['id']])
            ->delete()
            ->run();
    }

    public static function user()
    {
        return self::$user;
    }

    public static function check()
    {
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

        $authToken = self::$builder->table(self::$dbPrefix . 'auth_tokens')
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
        
        $user = self::$builder->table(self::$dbPrefix . 'users')
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

        self::$user = $user[0];

        return new self(self::$user);
    }
}