<?php

namespace libs;

use libs\QueryBuilder\src\QueryBuilder;

class Validator
{
    private static $builder = null;
    
    private static $rules = [
        "/^required$/" => [
            'method' => 'checkRequired',
            'message' => 'This field is required.',
        ],
        "/^integer$/" => [
            'method' => 'checkInteger',
            'message' => 'This field requires value of integer type.',
        ],
        "/^positiveInt$/" => [
            'method' => 'checkPositiveInt',
            'message' => 'This field requires value greater than zero.',
        ],
        "/^min:([0-9]+)$/" => [
            'method' => 'checkMinLength',
            'message' => 'This field requires longer string.',
        ],
        "/^email$/" => [
            'method' => 'checkEmail',
            'message' => 'This field must be a valid email address.',
        ],
        "/^unique:([a-zA-Z0-9\-\_]+):([a-zA-Z0-9\-\_]+):*([a-zA-Z0-9\-\_]*)$/" => [
            'method' => 'checkUnique',
            'message' => 'This value is already exists.',
        ],
        "/^exists:([a-zA-Z0-9\-\_]+):([a-zA-Z0-9\-\_]+)$/" => [
            'method' => 'checkExists',
            'message' => 'This value doesn\'t exists in database.',
        ],
        "/^included:\(([a-zA-Z0-9\-\_\,\s]+)\)$/" => [
            'method' => 'checkIncluded',
            'message' => 'This value doesn\'t included in available list of values.',
        ],
    ];

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

    private static function checkRequired($field)
    {
        if (isset($_POST[$field]))
        {
            return strlen($_POST[$field]) > 0;
        }

        return false;
    }

    private static function checkInteger($field)
    {
        if (isset($_POST[$field])) 
        {
            if (is_array($_POST[$field])) 
            {
                $isNumeric = true;

                foreach ($_POST[$field] as $row) 
                {
                    if (!is_numeric($row)) 
                    {
                        $isNumeric = false;
                    }

                }

                return $isNumeric;
            }

            return is_numeric($_POST[$field]);
        }

        return false;
    }

    private static function checkEmail($field)
    {
        if (isset($_POST[$field]))
        {
            return !!filter_var($_POST[$field], FILTER_VALIDATE_EMAIL);
        }

        return false;
    }

    private static function checkPositiveInt($field)
    {
        if (isset($_POST[$field])) 
        {
            return intval($_POST[$field]) > 0;
        }

        return false;
    }

    private static function checkMinLength($field, $minLength)
    {
        if (isset($_POST[$field])) 
        {
            return strlen($_POST[$field]) >= $minLength;
        }

        return false;
    }

    private static function checkUnique($field, $uTable, $uField, $exceptId = 0)
    {
        if (isset($_POST[$field]))
        {
            $isUnique = true;
            $fieldValue = $_POST[$field];
            
            $result = self::$builder->table($uTable)
                                    ->fields(['*'])
                                    ->select()
                                    ->run();

            foreach ($result as $res) 
            {
                if ($res[$uField] === $fieldValue
                    && $res['id'] !== +$exceptId) 
                {
                    $isUnique = false;
                }
            }

            return $isUnique;
        }

        return false;
    }

    private static function checkExists($field, $uTable, $uField)
    {
        if (isset($_POST[$field]))
        {
            $isExists = false;
            $fieldValue = $_POST[$field];
            
            $result = self::$builder->table($uTable)
                                    ->fields(['*'])
                                    ->select()
                                    ->run();

            foreach ($result as $res) 
            {
                if (+$res[$uField] === +$fieldValue) 
                {
                    $isExists = true;
                }
            }

            return $isExists;
        }

        return false;
    }

    private static function checkIncluded($field, $list)
    {
        if (isset($_POST[$field]))
        {
            $isIncluded = false;
            $fieldValue = $_POST[$field];
            $list = explode(',', $list);

            foreach ($list as $item)
            {
                if ($fieldValue === trim($item))
                {
                    $isIncluded = true;
                }
            }

            return $isIncluded;
        }

        return false;
    }

    public static function validate($array)
    {
        self::setBuilder();

        $errors = [];

        foreach ($array as $key => $val) 
        {
            $validateRules = explode('|', $val);
            $messages = [];

            foreach (self::$rules as $rKey => $rVal) 
            {
                foreach ($validateRules as $vrKey => $vrVal) 
                {
                    $matchResult = preg_match($rKey, $vrVal, $matches);

                    $first = isset($matches[1]) ? $matches[1] : null;
                    $second = isset($matches[2]) ? $matches[2] : null;
                    $third = isset($matches[3]) ? $matches[3] : null;

                    if ($matchResult) 
                    {
                        $methodName = $rVal['method'];
                        $message = $rVal['message'];

                        if (!self::$methodName($key, $first, $second, $third)) 
                        {
                            $messages[] = $message;
                        }
                    }
                }
            }

            if (count($messages)) 
            {
                $errors[$key] = $messages;
            }
        }

        return $errors;
    }
}
