<?php

namespace libs;

use libs\QueryBuilder\src\QueryBuilder;
use libs\Input;

class Validator
{
    private $errors;
    private static $builder;
    private static $dbPrefix = '';
    
    private static $rules = [
        "/^required$/" => [
            'method' => 'checkRequired',
            'message' => 'requiredMessage',
        ],
        "/^numeric$/" => [
            'method' => 'checkNumeric',
            'message' => 'numericMessage',
        ],
        "/^integer$/" => [
            'method' => 'checkInteger',
            'message' => 'integerMessage',
        ],
        "/^min:([0-9]+)$/" => [
            'method' => 'checkMin',
            'message' => 'minMessage',
        ],
        "/^max:([0-9]+)$/" => [
            'method' => 'checkMax',
            'message' => 'maxMessage',
        ],
        "/^minLength:([0-9]+)$/" => [
            'method' => 'checkMinLength',
            'message' => 'minLengthMessage',
        ],
        "/^email$/" => [
            'method' => 'checkEmail',
            'message' => 'emailMessage',
        ],
        "/^unique:([a-zA-Z0-9\-\_]+):([a-zA-Z0-9\-\_]+):*([a-zA-Z0-9\-\_]*)$/" => [
            'method' => 'checkUnique',
            'message' => 'uniqueMessage',
        ],
        "/^exists:([a-zA-Z0-9\-\_]+):([a-zA-Z0-9\-\_]+)$/" => [
            'method' => 'checkExists',
            'message' => 'existsMessage',
        ],
        "/^included:\(([a-zA-Z0-9\-\_\,\s]+)\)$/" => [
            'method' => 'checkIncluded',
            'message' => 'includedMessage',
        ],
        "/^alpha_dash$/" => [
            'method' => 'checkAlphaDash',
            'message' => 'alphaDashMessage',
        ],
    ];

    private static function requiredMessage()
    {
        return "This field is required.";
    }

    private static function numericMessage()
    {
        return "This field requires numeric value.";
    }

    private static function integerMessage()
    {
        return "This field requires integer value.";
    }

    private static function minMessage($min)
    {
        return "This field requires numeric value >= $min.";
    }

    private static function maxMessage($max)
    {
        return "This field requires numeric value <= $max.";
    }

    private static function minLengthMessage($length)
    {
        return "This field requires string longer than $length characters.";
    }

    private static function emailMessage()
    {
        return "This field must be a valid email address.";
    }

    private static function uniqueMessage()
    {
        return "This value is already exists in database.";
    }

    private static function existsMessage()
    {
        return "This value doesn't exists in database.";
    }

    private static function includedMessage($list)
    {
        return "This value doesn't included in available list of values: $list.";
    }

    private static function alphaDashMessage()
    {
        return "This field requires only alphanumeric characters with dashes, underscores and spaces.";
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
        if (empty($field) && $field !== '0')
        {
            return true;
        }

        return !!filter_var($field, FILTER_VALIDATE_EMAIL);
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

    private static function checkMax($field, $max)
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
                if (!is_numeric($row) || !(+$row <= +$max)) 
                {
                    $isValid = false;
                }

            }

            return $isValid;
        }

        return is_numeric($field) && +$field <= +$max;
    }

    private static function checkMinLength($field, $minLength)
    {
        return $field && strlen($field) >= $minLength;
    }

    private static function checkUnique($field, $uTable, $uField, $exceptId = 0)
    {
        if (empty($field) && $field !== '0')
        {
            return true;
        }

        $uTable = self::$dbPrefix . $uTable;

        $result = self::$builder->table($uTable)
                                ->fields(['*'])
                                ->where([$uField, '=', $field])
                                ->limit(1)
                                ->select()
                                ->run();

        if (count($result) !== 0)
        {
            return +$result[0]['id'] === +$exceptId;
        }

        return true;
    }

    private static function checkExists($field, $uTable, $uField)
    {
        if (empty($field) && $field !== '0')
        {
            return true;
        }
        
        $uTable = self::$dbPrefix . $uTable;
 
        if (!is_array($field))
        {
            $result = self::$builder->table($uTable)
                                    ->fields([$uField])
                                    ->where([$uField, '=', $field])
                                    ->limit(1)
                                    ->select()
                                    ->run();

            return count($result) > 0;
        }

        $sqlQuery = "SELECT $uField FROM $uTable WHERE";

        foreach($field as $row)
        {
            $sqlQuery .= " $uField = ? OR";
        }

        $sqlQuery = trim($sqlQuery, 'OR');

        $result = self::$builder->raw($sqlQuery, $field)->fetchAll(\PDO::FETCH_ASSOC);

        return count($result) === count($field);
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

    private static function checkAlphaDash($field)
    {
        if (empty($field) && $field !== '0')
        {
            return true;
        }

        return !!preg_match('/^[\w\s\-]+$/', $field);
    }

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function fails()
    {
        return count($this->errors) > 0;
    }

    public function errors()
    {
        return $this->errors;
    }

    public static function setDbPrefix($prefix)
    {
        self::$dbPrefix = $prefix;
    }

    public static function setBuilder(QueryBuilder $builder)
    {
        self::$builder = $builder;
    }

    public static function make(...$args)
    {
        if (count($args) > 1)
        {
            if (!is_array($args[0]) 
                || !is_array($args[1]))
            {
                throw new \Exception("Validator 'make' method requires array type arguments.");
            }

            if (array_keys($args[0]) !== array_keys($args[1]))
            {
                throw new \Exception("Validator 'make' method requires variables array and rules array to have equal keys.");
            }
        }

        $array = count($args) === 1 ? $args[0] : $args[1];
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

                        $fieldValue = count($args) === 1 ? Input::get($key) : $args[0][$key];

                        if (!self::$methodName($fieldValue, $first, $second, $third)) 
                        {
                            $messages[] = self::$message($first);
                        }
                    }
                }
            }

            if (count($messages)) 
            {
                $errors[$key] = $messages;
            }
        }

        return new self($errors);
    }
}
