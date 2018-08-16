<?php

namespace libs;

use libs\QueryBuilder\src\QueryBuilder;
use libs\Input;

class Validator
{
    private static $builder = null;
    
    private static $rules = [
        "/^required$/" => [
            'method' => 'checkRequired',
            'message' => 'This field is required.',
        ],
        "/^numeric$/" => [
            'method' => 'checkNumeric',
            'message' => 'This field requires numeric value.',
        ],
        "/^integer$/" => [
            'method' => 'checkInteger',
            'message' => 'This field requires integer value.',
        ],
        "/^min:([0-9]+)$/" => [
            'method' => 'checkMin',
            'message' => 'This field requires bigger numeric value.',
        ],
        "/^minLength:([0-9]+)$/" => [
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
        if (empty($field) && $field !== '0' && strlen($field) === 0)
        {
            return false;
        }

        return true;
    }

    private static function checkNumeric($field)
    {
        if ($field) 
        {
            if (is_array($field)) 
            {
                $isNumeric = true;

                foreach ($field as $row) 
                {
                    if (!is_numeric($row)) 
                    {
                        $isNumeric = false;
                    }

                }

                return $isNumeric;
            }

            return is_numeric($field);
        }

        return true;
    }

    private static function checkInteger($field)
    {
        if ($field) 
        {
            if (is_array($field)) 
            {
                $isInteger = true;

                foreach ($field as $row) 
                {
                    if (!ctype_digit(strval($row))) 
                    {
                        $isInteger = false;
                    }

                }

                return $isInteger;
            }

            return ctype_digit(strval($field));
        }

        return true;
    }

    private static function checkEmail($field)
    {
        return $field && !!filter_var($field, FILTER_VALIDATE_EMAIL);
    }

    private static function checkMin($field, $min)
    {
        if (empty($field) && $field !== '0')
        {
            return true;
        }

        if (is_array($field)) 
        {
            $isValid = true;

            foreach ($field as $row) 
            {
                if (!is_numeric($row) || !(+$row >= +$min)) 
                {
                    $isValid = false;
                }

            }

            return $isValid;
        }

        return is_numeric($field) && +$field >= +$min;

    }

    private static function checkMinLength($field, $minLength)
    {
        return $field && strlen($field) >= $minLength;
    }

    private static function checkUnique($field, $uTable, $uField, $exceptId = 0)
    {
        if ($field)
        {
            $isUnique = true;
            
            $result = self::$builder->table($uTable)
                                    ->fields(['*'])
                                    ->select()
                                    ->run();

            foreach ($result as $res) 
            {
                if ($res[$uField] === $field
                    && +$res['id'] !== +$exceptId) 
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
        if (empty($field) && $field !== '0')
        {
            return true;
        }
 
        $result = self::$builder->table($uTable)
                                ->fields([$uField])
                                ->select()
                                ->run();

        if (!is_array($field))
        {
            $isExists = false;

            foreach ($result as $res) 
            {
                if ($res[$uField] == $field) 
                {
                    $isExists = true;
                }
            }

            return $isExists;
        }

        $isExists = true;
        $resultValues = array_map(function($res) use ($uField) {
            return $res[$uField];
        }, $result);
        
        foreach ($field as $row)
        {
            if (!in_array($row, $resultValues))
            {
                $isExists = false;
            }
        }

        return $isExists;
    }

    private static function checkIncluded($field, $list)
    {
        if ($field)
        {
            $isIncluded = false;
            $list = explode(',', $list);

            foreach ($list as $item)
            {
                if ($field === trim($item))
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

                        if (!self::$methodName(Input::get($key), $first, $second, $third)) 
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
