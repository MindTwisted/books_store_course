<?php

namespace libs;

class Env
{
    private static $env = [];

    public static function setEnvFromFile($filePath)
    {
        $fileContents = file($filePath);

        foreach ($fileContents as $line)
        {
            $line = trim($line);

            if (strlen($line) === 0 
                || strpos($line, '#') !== false)
            {
                continue;
            }

            $envArr = explode('=', $line);
            self::$env[$envArr[0]] = $envArr[1];
        }
    }

    public static function get($var)
    {
        return isset(self::$env[$var]) ? self::$env[$var] : null;
    }

    public static function getEnv()
    {
        return self::$env;
    }
}