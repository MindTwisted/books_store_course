<?php

namespace libs;

class Input
{
    private static $input = [];

    public static function collectInput()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($_GET as $key => $value)
        {
            self::$input[$key] = $value;
        }

        if ('POST' === $method)
        {
            foreach ($_POST as $key => $value)
            {
                self::$input[$key] = $value;
            }
        }
       
        if ('PUT' === $method) {
            parse_str(file_get_contents('php://input'), $_PUT);

            foreach ($_PUT as $key => $value)
            {
                self::$input[$key] = $value;
            }
        }

        if ('DELETE' === $method) {
            parse_str(file_get_contents('php://input'), $_DELETE);

            foreach ($_DELETE as $key => $value)
            {
                self::$input[$key] = $value;
            }
        }
    }

    public static function get($field)
    {
        return isset(self::$input[$field]) ? self::$input[$field] : null;
    }

    public static function all()
    {
        return self::$input;
    }

    public static function only(array $fields)
    {
        return array_filter(self::$input, function($input) use ($fields) {
            return in_array($input, $fields);
        }, ARRAY_FILTER_USE_KEY);
    }
}